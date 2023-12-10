#!/bin/bash

arch=$(uname -m)

if [ "$arch" == "aarch64" ]; then
    echo "Host architecture is arm64."
    mv MythicalClientARM64 MythicalClient 
    chmod u+x ./MythicalClient
elif [ "$arch" == "armv7l" ]; then
    echo "Host architecture is arm32."
    mv MythicalClientARM32 MythicalClient
    chmod u+x ./MythicalClient
elif [ "$arch" == "x86_64" ]; then
    echo "Host architecture is amd64."
    mv MythicalClient64 MythicalClient
    chmod u+x ./MythicalClient
else
    echo "Unsupported architecture: $arch"
    exit 1
fi