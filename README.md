# resilient_replace

Search and replace, updating serialized text if needed

## Install

If you already have a `~/bin` directory in your `$PATH`, you can 

```bash
mkdir -p ~/bin && git clone https://github.com/pa-de-solminihac/resilient_replace.git ~/bin/resilient_replace_git && ln -s ~/bin/resilient_replace_git/resilient_replace ~/bin/resilient_replace
```


## Upgrade

```bash
cd ~/bin/resilient_replace_git && git pull
```


## Usage
```bash
resilient_replace <search_pattern> <replace> [<file>]
```

### Options
```
    -i
        edit file in place

    --only-into-serialized
        replace only into serialized data (do not replace into raw data)
```
