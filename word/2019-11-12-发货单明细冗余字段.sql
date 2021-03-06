ALTER TABLE `jshop_bill_delivery_items` DROP `order_items_id`;
ALTER TABLE `jshop_bill_delivery_items`
    ADD `goods_id` INT(11) UNSIGNED NOT NULL AFTER `delivery_id`,
    ADD `product_id` INT(11) UNSIGNED NOT NULL AFTER `goods_id`,
    ADD `sn` VARCHAR(30) NOT NULL AFTER `product_id`,
    ADD `bn` VARCHAR(30) NOT NULL AFTER `sn`,
    ADD `name` VARCHAR(200) NOT NULL AFTER `bn`;
ALTER TABLE `jshop_bill_delivery_items`
    ADD `weight` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `nums`,
    ADD `addon` TEXT NOT NULL AFTER `weight`;
ALTER TABLE `jshop_bill_delivery_items` CHANGE `addon` `addon` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;