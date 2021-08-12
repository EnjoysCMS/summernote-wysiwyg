Add to the project in the root **composer.json** to automatically install all the dependencies

```yaml
"scripts": {
  "post-install-cmd": "cd ./WYSIWYG/summernote && yarn install",
             ...
}
```

or run manually this command after install.

*Внимание! Если какие-то стили или шрифты не подключаются, необходимо самостоятельно в приложении создать симлинки*