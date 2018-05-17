# 迁移 elasticsearch 数据

## 说明

* 将一个索引的数据迁移到另一个索引之中
* 导出索引数据
* 导入索引数据
* 支持 elasticsearch 6 及以上版本

## 安装

```
$ docker-compose run --rm install
```

## 列出帮助文档

```
$ docker-compose run --rm console
```

## 复制 mapping 配置

* 如果 `to-index` 不存在，则按 `from-index` 的 `mapping` 创建
* 如果 `to-index` 已存在，则按 `from-index` 的 `mapping` 更新

```
$ docker-compose run --rm console cmd:copy:mapping \
  --from-host=192.168.1.100:9200 \
  --from-index=test-1 \
  --from-type=doc \ 
  --to-host=192.168.1.101:9200 \
  --to-index=test-2 \
  --to-type=doc
```

* 上面示例命令表示，将 192.168.1.100:9200/test-1/doc 的 mapping 更新到 192.168.1.101:9200/test-2/doc 中

## 复制索引数据

```
$ docker-compose run --rm console cmd:copy:index \
  --from-host=192.168.1.100:9200 \
  --from-index=test-1 \ 
  --from-type=doc \ 
  --to-host=192.168.1.101:9200 \
  --to-index=test-2 \
  --to-type=doc \
  --limit=10000
```

* 上面示例命令表示，将 192.168.1.100:9200/test-1/doc 的索引数据的前 10000 条记录同步到 192.168.1.101:9200/test-2/doc 中

## 导出 mapping 配置

```
$ docker-compose run --rm console cmd:export:mapping \
  --host=192.168.1.100:9200 \
  --index=test-1 \
  --type=doc \
  --output=test-1.doc.mapping
```

* 上面示例命令表示，将 192.168.1.100:9200/test-1/doc 的 mapping 配置导出到 `var/output/test-1.doc.mapping` 中

## 导入 mapping 配置

```
$ docker-compose run --rm console cmd:import:mapping \
  --host=192.168.1.100:9200 \
  --index=test-1 \
  --type=doc \
  --input=test-1.doc.mapping
```


* 上面示例命令表示，将 `var/output/test-1.doc.mapping` 的配置更新到 192.168.1.100:9200/test-1/doc 的 mapping 配置中

## 导出索引数据

```
$ docker-compose run --rm console cmd:export:index \
  --host=192.168.1.100:9200 \
  --index=test-1 \
  --type=doc \
  --limit=10000 \
  --output=test-1.doc.index
```

* 上面示例命令表示，将 192.168.1.100:9200/test-1/doc 的前 10000 条记录导出到 `var/output/test-1.doc.index` 中

## 导入索引数据

```
$ docker-compose run --rm console cmd:import:index \
  --host=192.168.1.100:9200 \
  --index=test-1 \
  --type=doc \
  --input=test-1.doc.output
```

* 上面示例命令表示，将 `var/output/test-1.doc.output` 的内容导入到 192.168.1.100:9200/test-1/doc 的索引数据中
