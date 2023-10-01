Первая установка
-------------------
В базе дынных только одна таблица
```postgresql
create table messages
(
    id               bigserial         primary key,
    name             text              not null,
    content          text              not null,
    ip               varchar(40)       not null,
    created_at       integer           DEFAULT EXTRACT(EPOCH FROM NOW())
);
```