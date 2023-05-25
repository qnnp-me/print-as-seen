<?php
echo "==========测试代码错误...\n";
$test_result = shell_exec('tsc --noEmit');
if ($test_result) {
  echo "==========发现错误：\n";
  echo $test_result . PHP_EOL;
  exit;
}
echo "==========测试通过.\n";
echo "==========重建引用文件 src/ref.ts ...\n";
$ref_file = 'src/ref.ts';
$old_file = file_get_contents($ref_file);
$reference = shell_exec("find -E ./src/types -regex '.*\.ts$' |  perl -pe 's/(\.\/src\/)(.*)/\/\/\/ <reference path=\"$2\" \/>/g'");
$new_data = preg_replace('/(\/\/ *AUTO REF\n)(.*)(\/\/ *AUTO REF\n)/ms', "$1$reference$3", $old_file);
file_put_contents($ref_file, $new_data);
echo "==========删除旧的文件...\n";
echo shell_exec('rm -rf -v dist types | awk \'{print "rm -rf "$0}\'');
echo "==========构建中...\n";
echo shell_exec('tsc --build -v');
echo "==========复制必要的文件...\n";
echo "cp -v " . shell_exec('cp -v js/Multipart.min.js dist/utils');
echo "==========删除不需要的文件...\n";
echo shell_exec('rm -rf -v dist/types types/utils | awk \'{print "rm -rf "$0}\'');
echo "==========生成的文件：\n";
echo shell_exec("find -E . -regex '^\.\/(dist|types).*\..*' |  perl -pe 's/^(\.\/)(.*)/$2/g'");
echo "==========构建完成\n";
