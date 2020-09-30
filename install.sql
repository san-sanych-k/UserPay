DELETE FROM `{#}controllers` WHERE `name` = 'userpay';
INSERT INTO `{#}controllers` (`title`, `name`, `slug`, `is_enabled`, `options`, `author`, `url`, `version`, `is_backend`, `is_external`, `files`, `addon_id`) VALUES
('User Pay - Платежные системы', 'userpay', NULL, 1, '---\ncurr_short: руб.\ncurr_name: рубль|рубля|рублей\nin_notice:\n  - 1\nbalance_on: 1\nbalance_name: >\n  Внутренний кошелек\n  сайта\nbalance_curr_short: руб.\nbalance_in: 1\nbalance_fee:\nbalance_order: 5\nyandex_on: null\nyandex_name: Яндекс.Деньги\nyandex_curr_short: руб.\nyandex_wallet: \nyandex_secret: \nyandex_in: 1\nyandex_fee:\nyandex_order: 1\nwebmoney_on: null\nwebmoney_name: WebMoney\nwebmoney_curr_short: руб.\nwebmoney_wallet: \nwebmoney_secret: \nwebmoney_sim: null\nwebmoney_in: 1\nwebmoney_fee:\nwebmoney_order: 3\npayments_list_style: basic\nyandex_hint: >\n  Яндекс. Кошелек,\n  Visa/Mastercard (РФ), Со счета\n  мобильного\nbalance_hint: >\n  С внутреннего счета\n  на сайте\nwebmoney_hint: С кошелька WebMoney\nref_inner_text: >\n  Вы перешли на сайт по\n  партнерской ссылке\n  пользователя сайта\n', 'Kreator Dev.', 'http://www.instantcms.ru/users/kreator', '1.2.2', 1, 1, NULL, 257);

DELETE FROM `{#}scheduler_tasks` WHERE `controller` = 'userpay';
INSERT INTO `{#}scheduler_tasks` (`title`, `controller`, `hook`, `period`, `is_strict_period`, `date_last_run`, `is_active`, `is_new`) VALUES
('Задачи Userpay', 'userpay', 'job', 1, NULL, NULL, 1, 1);

DROP TABLE IF EXISTS `{#}userpay_donate`;
CREATE TABLE IF NOT EXISTS `{#}userpay_donate` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `target_id` varchar(100) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT '0',
  `amount` decimal(10,2) UNSIGNED NOT NULL,
  `date_pub` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_history`;
CREATE TABLE IF NOT EXISTS `{#}userpay_history` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `amount` DECIMAL(24,2) NOT NULL DEFAULT '0.00',
  `title` varchar(500) NOT NULL,
  `is_pay` tinyint(1) UNSIGNED DEFAULT NULL,
  `date_pub` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ref` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_invoice`;
CREATE TABLE IF NOT EXISTS `{#}userpay_invoice` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `data_item` text,
  `amount` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) UNSIGNED DEFAULT '1',
  `date_pub` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_log`;
CREATE TABLE IF NOT EXISTS `{#}userpay_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `system` varchar(100) NOT NULL,
  `tr_id` varchar(100) NOT NULL,
  `tr_subject` text NOT NULL,
  `date_pub` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tr_id` (`tr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_paid_fields`;
CREATE TABLE IF NOT EXISTS `{#}userpay_paid_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT '0',
  `controller` varchar(32) DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `field_id` int(10) unsigned DEFAULT NULL,
  `date_pub` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_subscriptions`;
CREATE TABLE IF NOT EXISTS `{#}userpay_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `end_date` varchar(32) NOT NULL,
  `groups` int(11) NOT NULL,
  `date_pub` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pub_key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_subscription_list`;
CREATE TABLE IF NOT EXISTS `{#}userpay_subscription_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `desc` text,
  `period` int(11) NOT NULL,
  `period_type` varchar(16) NOT NULL,
  `groups` text,
  `is_active` int(1) DEFAULT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_partner_log`;
CREATE TABLE IF NOT EXISTS `{#}userpay_partner_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(40) NULL DEFAULT NULL,
  `page` varchar(1000) NULL DEFAULT NULL,
  `domain` varchar(300) NULL DEFAULT NULL,
  `auth` int(11) UNSIGNED NULL DEFAULT NULL,
  `user_agent` varchar(500) NULL DEFAULT NULL,
  `cookie` varchar(32) NULL DEFAULT NULL,
  `date_pub` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{#}userpay_partner_users`;
CREATE TABLE IF NOT EXISTS `{#}userpay_partner_users` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ref_id` int(11) UNSIGNED NOT NULL,
  `level` int(11) UNSIGNED NOT NULL,
  `date_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DELETE FROM `{#}users_tabs` WHERE `controller` = 'userpay';

INSERT INTO `{#}users_tabs` (`title`, `controller`, `name`, `is_active`, `ordering`, `groups_view`, `groups_hide`, `show_only_owner`) VALUES
('Партнеры', 'userpay', 'partner', 1, 3, '---\n- 0\n', '---\n- 1\n', NULL);
INSERT INTO `{#}users_tabs` (`title`, `controller`, `name`, `is_active`, `ordering`, `groups_view`, `groups_hide`, `show_only_owner`) VALUES
('Кошелек', 'userpay', 'pay', 1, 5, '---\n- 0\n', NULL, NULL);

DELETE FROM `{#}widgets` WHERE `controller` = 'userpay';
INSERT INTO `{#}widgets` (`controller`, `name`, `title`, `author`, `url`, `version`, `is_external`, `files`, `addon_id`) VALUES
('userpay', 'donate', 'Donate', 'Kreator', 'https://instantcms.su', '1.2.1', 1, NULL, NULL);
INSERT INTO `{#}widgets` (`controller`, `name`, `title`, `author`, `url`, `version`, `is_external`, `files`, `addon_id`) VALUES
('userpay', 'subscription', 'Подписка', 'Kreator', 'https://instantcms.su', '1.2.1', 1, NULL, NULL);