# 迁移 elasticsearch 数据

## 安装

```
$ docker-compose run --rm app composer install
```

## 复制 mapping 

```
$ docker-compose run --rm app \
  --from-host=192.168.1.100 \
  --from-index=test-1 \ 
  --to-host=192.168.1.101 \
  --to-index=test-2 \
  --type=mapping
```

## 复制索引数据
```
$ docker-compose run --rm app \
  --from-host=192.168.1.100 \
  --from-index=test-1 \ 
  --to-host=192.168.1.101 \
  --to-index=test-2 \
  --type=index
```

