# 迁移 elasticsearch 数据

## 说明

* 将一个索引的数据迁移到另一个索引之中
* 支持 elasticsearch 6 及以上版本

## 安装

```
$ docker-compose run --rm app composer install
```

## 复制 mapping 

* 如果 `to-index` 不存在，则按 `from-index` 的 `mapping` 创建
* 如果 `to-index` 已存在，则按 `from-index` 的 `mapping` 更新

```
$ docker-compose run --rm app \
  --from-host=192.168.1.100 \
  --from-index=test-1 \ 
  --to-host=192.168.1.101 \
  --to-index=test-2 \
  --action=mapping
```

## 复制索引数据

* 全量数据同步，每次 1000 个记录批量同步

```
$ docker-compose run --rm app \
  --from-host=192.168.1.100 \
  --from-index=test-1 \ 
  --to-host=192.168.1.101 \
  --to-index=test-2 \
  --action=index
```

