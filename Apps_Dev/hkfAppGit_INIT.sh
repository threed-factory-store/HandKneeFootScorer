#!/bin/bash

. ~/Documents/Apps/hkfAppCommon

# This assumes we have an SSH key that's already been set up with GitHub.com
# https://gist.github.com/xirixiz/b6b0c6f4917ce17a90e00f9b60566278

git init
git add .
git remote add origin git@github.com:threed-factory-store/HandKneeFootScorer.git
git push --set-upstream origin main
git commit -m "initial commit"
git push
