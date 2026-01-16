#!/bin/sh


CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [[ "$CURRENT_BRANCH" == "beta" ]]; then
  BRANCH="beta"
elif [[ "$CURRENT_BRANCH" == "prod" ]]; then
  BRANCH="prod"
  LATEST=1
else
  echo "Unknown branch: $CURRENT_BRANCH"
  exit 1
fi

docker build \
    -t sigblue/webio:$BRANCH .
    
if [ "$LATEST" = 1 ]; then
    docker tag sigblue/webio:$BRANCH sigblue/webio:latest
fi

# Ask user if push
read -p "Do you want to push the image? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    docker push sigblue/webio:$BRANCH
    if [ "$LATEST" = 1 ]; then
        docker push sigblue/webio:latest
    fi
fi

