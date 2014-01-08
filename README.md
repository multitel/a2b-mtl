A2B-Mtl
===================

This project provides an interface for Multitel's (www.multitel.net) phone numbers/DID API.

It allows the following functions:
 - Purchase and edit of purchased numbers from a2b customer/enduser and admin interfaces
 - E911 provisioning for US and Canada numbers from a2b customer/enduser and admin interfaces
 - Enabling features such as Fax Autodetect , telemarketing calls autodetect (and kill), etc
 - Fax inbox/outbox access from a2billing customer/end-user interface
 - SMS/Text message sending
 - Defining markups and service minimum payments for Multitel 
 
Installation:
===================

---------------------------------------------------------------------------------
Step 1:  You'll have to create four new tables
---------------------------------------------------------------------------------

```mysql
CREATE TABLE `mtl_rcvd_fax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_card_id` int(11) DEFAULT NULL,
  `cc_did_id` int(11) DEFAULT NULL,
  `cc_didgroup_id` int(11) DEFAULT NULL,
  `mtl_fax_id` int(11) DEFAULT NULL,
  `fax_nr` varchar(18) DEFAULT NULL,
  `src_nr` varchar(18) DEFAULT NULL,
  `duration` int(6) DEFAULT NULL,
  `pages` int(6) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `bitrate` varchar(18) DEFAULT NULL,
  `pdf` longblob,
  `received` datetime DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
--

CREATE TABLE `mtl_sent_fax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_card_id` int(11) DEFAULT NULL,
  `cc_did_id` int(11) DEFAULT NULL,
  `cc_didgroup_id` int(11) DEFAULT NULL,
  `mtl_fax_id` int(11) DEFAULT NULL,
  `src_nr` varchar(18) DEFAULT NULL,
  `dst_nr` varchar(18) DEFAULT NULL,
  `duration` int(6) DEFAULT NULL,
  `pages` int(6) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `pdf` longblob,
  `sent` datetime DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
--

CREATE TABLE `mtl_sent_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_card_id` int(11) DEFAULT NULL,
  `src_nr` varchar(20) DEFAULT NULL,
  `dst_nr` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
--

CREATE TABLE `mtl_services` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(40) DEFAULT NULL,
  `did_group_id` int(5) NOT NULL DEFAULT '1',
  `did_setup_markup` int(3) DEFAULT NULL,
  `did_setup_minimum` decimal(8,3) DEFAULT NULL,
  `did_monthly_markup` int(3) DEFAULT NULL,
  `did_monthly_minimum` decimal(8,3) DEFAULT NULL,
  `did_minute_markup` int(3) DEFAULT NULL,
  `did_minute_minimum` decimal(8,3) DEFAULT NULL,
  `did_freeminutes_maximum` int(5) DEFAULT NULL,
  `sms_markup` int(3) DEFAULT NULL,
  `e911_price` decimal(8,3) DEFAULT NULL,
  `fax_price` decimal(8,3) DEFAULT '0.000',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
```

---------------------------------------------------------------------------------
Step 2:  Creating new DID groups 
---------------------------------------------------------------------------------
```mysql
insert into cc_didgroup set didgroupname='Starter';
insert into cc_didgroup set didgroupname='Professional';
insert into cc_didgroup set didgroupname='Business';
```
---------------------------------------------------------------------------------
Step 3:  Creating sample services
---------------------------------------------------------------------------------
```mysql
INSERT INTO `mtl_services` VALUES (1,'Starter',1,5,'3.000',5,'5.000',5,'0.020',1500,10,'0.990','0.5','');
INSERT INTO `mtl_services` VALUES (2,'Professional',2,4,'2.500',4,'4.000',4,'0.015',2000,8,'0.960','0.5','');
INSERT INTO `mtl_services` VALUES (3,'Business',2,3,'2.00',4,'3.000',3,'0.010',3000,6,'0.930','0.5','');
```

---------------------------------------------------------------------------------
Step 4:  Adding links in customer interface
---------------------------------------------------------------------------------

- Go into customer folder  (cd /your/path/to/a2b/customer )
- edit templates/default/main.tpl , go to line 61 (just after the {/if} statement closure)
- Add the following three lines:

```html
<div class="toggle_menu"><li><a href="mtl.intldid.php?action=list"><STRONG>International DIDs</strong></a></li></div>
<div class="toggle_menu"><li><a href="mtl.faxmessages.php?action=list"><STRONG>FAX MESSAGES</strong></a></li></div>
<div class="toggle_menu"><li><a href="mtl.smslogs.php?action=list"><STRONG>SMS</strong></a></li></div>
```
(in addition to that, there is a sample main.tpl file included in the the tmp folder, which you could use to replace your own main.tpl file if yours has no custom mods)


