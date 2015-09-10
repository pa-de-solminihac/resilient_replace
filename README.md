# Search, replace and update `JSON`/serialized text

With support for pipes, regexes, and efficiently using memory for large files

## Examples

Replace _domain.com_ with _newdomain.com_ in a **dump.sql** file

```bash
resilient_replace -i 'domain.com' 'newdomain.com' dump.sql
```

### Regular expressions support (PCRE regex)

Using `--regex` your can replace _test**0**.domain.com_ or _test**11**.domain.com_ but not _test**ing**.domain.com_

```bash
resilient_replace -i --regex 'test[0-9]*.domain\.com' 'newdomain.com' dump.sql
```

### Pipes support
```bash
cat file.json | resilient_replace 'domain.com' 'newdomain.com'
```


## Usage
```bash
resilient_replace <search_pattern> <replace> [<file>]
```


### Options
```
    -i, --in-place
        edit file in place

    --regex
        treat <search_pattern> as a regex

    --only-into-serialized
        replace only into serialized data (do not replace into raw data)
```


## Install

If you already have a `~/bin` directory in your `$PATH`, you can just paste this in a terminal:

```bash
mkdir -p ~/bin && \
git clone https://github.com/pa-de-solminihac/resilient_replace.git ~/bin/resilient_replace_git && \
ln -s ~/bin/resilient_replace_git/resilient_replace ~/bin/resilient_replace
```


### Upgrade

```bash
cd ~/bin/resilient_replace_git && git pull
```

