create table "order"
(
    id         integer          not null
        primary key,
    created_at timestamp        not null,
    status     varchar(255)     not null,
    total      double precision not null
);

alter table "order"
    owner to root;

create table order_item
(
    id           serial
        primary key,
    product_id   varchar(255)     not null,
    product_name varchar(255)     not null,
    price        double precision not null,
    quantity     integer          not null,
    order_id     integer
        constraint fk_order_item_order_id
            references "order"
);

alter table order_item
    owner to root;

create index idx_order_item_order_id
    on order_item (order_id);

create table "user"
(
    id       serial
        primary key,
    username varchar(180) not null
        unique,
    roles    json         not null,
    password varchar(255) not null
);

alter table "user"
    owner to root;

