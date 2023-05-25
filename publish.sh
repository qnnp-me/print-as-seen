#
# Copyright (c) 2023. qnnp <qnnp@qnnp.me> https://qnnp.me
#

echo "Do you wish to publish $1?"
select yn in "Yes" "No"; do
  case $yn in
  Yes)
    printf "==========testing...\n"
    test=$(tsc --noEmit)
    if [ "$test" != "" ]; then
      printf "==========test error\n"
      printf "%s\n" "$test"
      exit
    fi
    printf "==========test success\n"
    printf "==========building...\n"
    php build.php
    printf "==========git commit and push dev...\n"
    git add .
    git commit -a -m "publish version $1"
    git push origin dev --tags
    printf "==========git checkout main and merge dev...\n"
    git checkout main
    git merge -m "Merge branch 'dev' for publish version $1" dev
    printf "==========git push main...\n"
    git push origin main --tags
    printf "==========change package version NO.\n"
    npm version $1
    printf "==========publish package...\n"
    npm publish
    printf "==========git push main...\n"
    git push origin main --tags
    printf "==========git checkout dev...\n"
    git checkout dev
    printf "==========all done...\n"
    break
    ;;
  No)
    printf 'Bye.\n'
    exit
    ;;
  esac
done
