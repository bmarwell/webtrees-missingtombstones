BUILD_DIR=build
LANGUAGE_DIR=language
LANGUAGE_SRC=$(shell git grep -I --name-only --fixed-strings -e I18N:: -- "*.php" "*.xml")
MO_FILES=$(patsubst %.po,%.mo,$(PO_FILES))
PO_FILES=$(wildcard $(LANGUAGE_DIR)/*.po)
SHELL=bash
MKDIR=mkdir -p

.PHONY: clean update vendor build/missingtombstones

.PHONY: all
all: $(LANGUAGE_DIR)/messages.pot update build/missingtombstones.tar.bz2

.PHONY: clean
clean:
	rm -Rf build/* $(LANGUAGE_DIR)/messages.pot
	rm -Rf build

.PHONY: update
update: $(LANGUAGE_DIR)/messages.pot $(MO_FILES)

.PHONY: vendor
vendor:
	php ./composer.phar self-update
	php ./composer.phar update
	php ./composer.phar dump-autoload --optimize

.PHONY: build/missingtombstones
build/missingtombstones: $(LANGUAGE_DIR)/messages.pot update
	$(MKDIR) build/missingtombstones
	cp -R *.php build/missingtombstones/
	cp -R $(LANGUAGE_DIR) build/missingtombstones/

build/missingtombstones.tar.bz2: build/missingtombstones
	tar cvjf $@ $^

$(LANGUAGE_DIR)/messages.pot: $(LANGUAGE_SRC)
	echo $^ | xargs xgettext --package-name="webtrees-missingtombstones" --package-version=1.0 --msgid-bugs-address=bmarwell@gmail.com --no-wrap --language=PHP --add-comments=I18N --from-code=utf-8 --keyword=translate:1 --keyword=translateContext:1c,2 --keyword=plural:1,2 --output=$@

$(PO_FILES): $(LANGUAGE_DIR)/messages.pot
	msgmerge --no-wrap --sort-output --no-fuzzy-matching --output=$@ $@ $<

%.mo: %.po
	msgfmt --output=$@ $<

# vim:noexpandtab
