#!/bin/sh

if [ ! $1 ]; then
    echo 'tag not set'
    exit 2;
fi

TAG=$1
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [[ "$TAG" == "beta" ]]; then
  BRANCH="beta"
elif [[ "$TAG" == "prod" ]]; then
  BRANCH="prod"
  LATEST=1
else
  echo "Unknown tag: $TAG"
  exit 1
fi

trap "git checkout $CURRENT_BRANCH" EXIT
git checkout $BRANCH

docker build -t sigblue/webio:$TAG .
    
if [ "$LATEST" = 1 ]; then
    docker tag sigblue/webio:$TAG sigblue/webio:latest
fi

# Ask user if push
read -p "Do you want to push the image? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    docker push sigblue/webio:$TAG
    if [ "$LATEST" = 1 ]; then
        docker push sigblue/webio:latest
    fi
fi

git checkout $CURRENT_BRANCH
