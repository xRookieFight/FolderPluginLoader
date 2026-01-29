# FolderPluginLoader

**FolderPluginLoader** is a custom plugin loader designed specifically for **AquaRelay**.
It allows plugins to be loaded **from folders instead of single PHAR files**, making development, debugging, and hot-reloading significantly easier.

This loader is especially useful for developers who prefer working with raw source code during development.

---

## 📁 Folder Structure

Each plugin must be placed inside the `plugins/` directory as a folder.

Example:

```
plugins/
 └── ExamplePlugin/
     ├── plugin.yml
     └── src/
         └── example/
             └── Main.php
```

> [!WARNING]
> `plugin.yml` is **required** and must be located in the root of the plugin folder.

---

## 🚀 Installation

1. Download **FolderPluginLoader** PHAR file from releases.
2. Place it into AquaRelay's `plugins/` directory.
3. Restart your server.

---

## ⚠️ Production Warning

Using folder-based plugins in production is **not recommended**.

For production:

* Package your plugins as **PHAR**
* Disable FolderPluginLoader if possible

This plugin is meant for **development, testing, and debugging only**.

---

## 📜 License

This project is licensed under the **LGPL-3.0 License**
You are free to modify and redistribute it under the same license.

---

## 💬 Credits

Developed for **AquaRelay**
Made with ❤️ for developers who are lazy :p

---