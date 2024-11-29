FROM ubuntu:latest
LABEL authors="hatem"

ENTRYPOINT ["top", "-b"]