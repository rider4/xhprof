<?php

class XHPROF_Helper
{
    /**
     * @param int $flags XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS
     */
    public static function startProfiling(
        $flags = XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS
    ) {
        xhprof_enable($flags);
    }

    /**
     * @param string $namespace
     * @param string $nameOfTest
     * @param string $host
     * @return string Return link to result of current test
     */
    public static function stopProfiling(
        string $namespace,
        string $nameOfTest,
        $host = 'xhprof'
    ): string {
        $xhprof_data = xhprof_disable();

        $XHPROF_ROOT = realpath(
            dirname(__FILE__).'/../xhprof_lib'
        );
        include_once $XHPROF_ROOT."/utils/xhprof_lib.php";
        include_once $XHPROF_ROOT."/utils/xhprof_runs.php";

        $namespace  = str_replace('.', '_', $namespace);
        $nameOfTest = str_replace('.', '_', $nameOfTest);

        $linkName    = sprintf(
            '%s_%s',
            $namespace,
            $nameOfTest
        );
        $xhprof_runs = new \XHProfRuns_Default();
        $run_id      = $xhprof_runs->save_run($xhprof_data, $linkName);

        return sprintf(
            'http://%s/index.php?run=%s&source=%s',
            $host,
            $run_id,
            $linkName
        );
    }
}