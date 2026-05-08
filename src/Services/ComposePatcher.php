<?php

namespace Matheusmarnt\Scoutify\Services;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class ComposePatcher
{
    public function __construct(private readonly string $path) {}

    public function patchDependsOn(string $appService, string $dependency): bool
    {
        if (! is_file($this->path)) {
            throw new RuntimeException("Compose file not found: {$this->path}");
        }

        $compose = Yaml::parseFile($this->path);

        if (! isset($compose['services'][$appService])) {
            throw new RuntimeException("Service '{$appService}' not found in {$this->path}");
        }

        $service = $compose['services'][$appService];
        $dependsOn = $service['depends_on'] ?? null;

        $alreadyHealthy = is_array($dependsOn)
            && isset($dependsOn[$dependency])
            && is_array($dependsOn[$dependency])
            && ($dependsOn[$dependency]['condition'] ?? null) === 'service_healthy';

        if ($alreadyHealthy) {
            return false;
        }

        $this->backup();

        $compose['services'][$appService]['depends_on'] = $this->mergeDependency($dependsOn, $dependency);

        file_put_contents(
            $this->path,
            Yaml::dump($compose, inline: 6, indent: 4, flags: Yaml::DUMP_OBJECT_AS_MAP)
        );

        return true;
    }

    /** @param mixed $dependsOn */
    private function mergeDependency($dependsOn, string $dependency): array
    {
        if ($dependsOn === null) {
            return [$dependency => ['condition' => 'service_healthy']];
        }

        if (is_array($dependsOn) && array_is_list($dependsOn)) {
            $map = [];
            foreach ($dependsOn as $entry) {
                if ($entry === $dependency) {
                    continue;
                }
                $map[$entry] = ['condition' => 'service_started'];
            }
            $map[$dependency] = ['condition' => 'service_healthy'];

            return $map;
        }

        /** @var array<string, mixed> $dependsOn */
        $dependsOn[$dependency] = ['condition' => 'service_healthy'];

        return $dependsOn;
    }

    private function backup(): string
    {
        $backupPath = $this->path.'.scoutify-backup-'.date('Ymd-His');
        copy($this->path, $backupPath);

        return $backupPath;
    }
}
