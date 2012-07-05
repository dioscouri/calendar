-- -----------------------------------------------------
-- HOW TO USE THIS FILE:
-- Replace all instances of #_ with your prefix
-- In PHPMYADMIN or the equiv, run the entire SQL
-- -----------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

drop table if exists `#__calendar_addresses`;
drop table if exists `#__calendar_carts`;
drop table if exists `#__calendar_categories`;
drop table if exists `#__calendar_config`;
drop table if exists `#__calendar_countries`;
drop table if exists `#__calendar_currencies`;
drop table if exists `#__calendar_geozones`;
drop table if exists `#__calendar_geozonetypes`;
drop table if exists `#__calendar_manufacturers`;
drop table if exists `#__calendar_ordercoupons`;
drop table if exists `#__calendar_orderhistory`;
drop table if exists `#__calendar_orderinfo`;
drop table if exists `#__calendar_orderitems`;
drop table if exists `#__calendar_orderitemattributes`;
drop table if exists `#__calendar_orderpayments`;
drop table if exists `#__calendar_orders`;
drop table if exists `#__calendar_ordershippings`;
drop table if exists `#__calendar_orderstates`;
drop table if exists `#__calendar_ordertaxclasses`;
drop table if exists `#__calendar_ordertaxrates`;
drop table if exists `#__calendar_ordervendors`;
drop table if exists `#__calendar_productattributeoptions`;
drop table if exists `#__calendar_productattributes`;
drop table if exists `#__calendar_productcategoryxref`;
drop table if exists `#__calendar_productcomments`;
drop table if exists `#__calendar_productcommentshelpfulness`;
drop table if exists `#__calendar_productdownloadlogs`;
drop table if exists `#__calendar_productdownloads`;
drop table if exists `#__calendar_productfiles`;
drop table if exists `#__calendar_productprices`;
drop table if exists `#__calendar_productquantities`;
drop table if exists `#__calendar_productrelations`;
drop table if exists `#__calendar_productreviews`;
drop table if exists `#__calendar_products`;
drop table if exists `#__calendar_productvotes`;
drop table if exists `#__calendar_shippingmethods`;
drop table if exists `#__calendar_shippingrates`;
drop table if exists `#__calendar_subscriptions`;
drop table if exists `#__calendar_subscriptionhistory`;
drop table if exists `#__calendar_taxclasses`;
drop table if exists `#__calendar_taxrates`;
drop table if exists `#__calendar_userinfo`;
drop table if exists `#__calendar_zonerelations`;
drop table if exists `#__calendar_zones`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;