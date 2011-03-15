#!/bin/bash
cd "`dirname $0`/.."
./scripts/upload.py --cc "candle-devel+codereview@googlegroups.com" --base_url "http://ho.st.dcs.fmph.uniba.sk/~mato/hg/candle" "$@"

