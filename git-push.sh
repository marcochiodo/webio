#!/bin/sh

if [ `git rev-parse --verify main 2>/dev/null` ]
then
   MAIN_BRANCH='main';
else
    if [ `git rev-parse --verify master 2>/dev/null` ]
    then
        MAIN_BRANCH='master';
    else
        echo 'No main or master found';
        exit 1;
    fi
fi

if [ ! $1 ]
then
    echo 'branch not set'
    exit 2;
fi


git checkout $MAIN_BRANCH
git push
git checkout $1
git merge $MAIN_BRANCH
git push
git checkout $MAIN_BRANCH