<?php
define('WORLD', 1022);
define ('NON_CLIENT', 0);

// kill_mode
define('MOD_UNKNOWN', 0);
define('MOD_WATER', 1);
define('MOD_SLIMED', 2);
define('MOD_LAVA', 3);
define('MOD_CRUSHED', 4);
define('MOD_TELEFRAG', 5);
define('MOD_FALLING', 6);
define('MOD_SUICIDE', 7);
define('MOD_LASER_TARGET', 8);
define('MOD_TRIGGER_HURT', 9);
define('MOD_CHANGE_TEAM', 10);
define('UT_MOD_KNIFE', 12);
define('UT_MOD_KNIFE_THROWN', 13);
define('UT_MOD_BERETTA', 14);
define('UT_MOD_DEAGLE', 15);
define('UT_MOD_SPAS', 16);
define('UT_MOD_UMP45', 17);
define('UT_MOD_MP5K', 18);
define('UT_MOD_LR300', 19);
define('UT_MOD_G36', 20);
define('UT_MOD_PSG1', 21);
define('UT_MOD_HK69', 22);
define('UT_MOD_BLEED', 23);
define('UT_MOD_KICKED', 24);
define('UT_MOD_HEGRANADE', 25);
define('UT_MOD_FLASH', 26);
define('UT_MOD_SMOKE', 27);
define('UT_MOD_SR8', 28);
define('UT_MOD_SUICIDE', 29);
define('UT_MOD_AK103', 30);
define('UT_MOD_SPLODED', 31);
define('UT_MOD_SLAPPED', 32);
#define('UT_MOD_NEGEV', 35);
define('UT_MOD_NEGEV', 36);
define('UT_MOD_HK69_HIT', 37);
define('UT_MOD_M4', 38);
define('UT_MOD_GLOCK', 39);
define('UT_MOD_COLT1911', 40);
define('UT_MOD_MAC', 41);
define('UT_MOD_FLAG', 42);
define('UT_MOD_GOOMBA', 43);

$WEAPON_KILL[MOD_UNKNOWN] = "Unknown Damage";
$WEAPON_KILL[MOD_WATER] = "Drowning";
$WEAPON_KILL[MOD_SLIMED] = "Got Slimed";
$WEAPON_KILL[MOD_LAVA] = "Meltdown";
$WEAPON_KILL[MOD_CRUSHED] = "Crushed";
$WEAPON_KILL[MOD_TELEFRAG] = "Telefragged";
$WEAPON_KILL[MOD_FALLING] = "Doing the Lemming thing";
$WEAPON_KILL[MOD_SUICIDE] = "Suicide";
$WEAPON_KILL[MOD_LASER_TARGET] = "Laser Target";
$WEAPON_KILL[MOD_TRIGGER_HURT] = "Damage by triggers";
$WEAPON_KILL[MOD_CHANGE_TEAM] = "Changing Team";
$WEAPON_KILL[UT_MOD_KNIFE] = "Cut by Knife";
$WEAPON_KILL[UT_MOD_KNIFE_THROWN] = "Thrown Knife";
$WEAPON_KILL[UT_MOD_BERETTA] = "Beretta";
$WEAPON_KILL[UT_MOD_DEAGLE] = "Desert Eagle";
$WEAPON_KILL[UT_MOD_SPAS] = "Spas 12";
$WEAPON_KILL[UT_MOD_UMP45] = "UMP 45";
$WEAPON_KILL[UT_MOD_MP5K] = "MP5K";
$WEAPON_KILL[UT_MOD_LR300] = "LR300";
$WEAPON_KILL[UT_MOD_G36] = "G36";
$WEAPON_KILL[UT_MOD_PSG1] = "PSG1";
$WEAPON_KILL[UT_MOD_HK69] = "HK 69";
$WEAPON_KILL[UT_MOD_BLEED] = "Excessive Bloodloss";
$WEAPON_KILL[UT_MOD_KICKED] = "Got kicked";
$WEAPON_KILL[UT_MOD_HEGRANADE] = "High Explosive Grenade";
$WEAPON_KILL[UT_MOD_FLASH] = "Flash Grenade";
$WEAPON_KILL[UT_MOD_SMOKE] = "Smoke Grenade";
$WEAPON_KILL[UT_MOD_SR8] = "SR8";
$WEAPON_KILL[UT_MOD_SUICIDE] = "Sacrificed his life";
$WEAPON_KILL[UT_MOD_AK103] = "AK 103";
$WEAPON_KILL[UT_MOD_SPLODED] = "Exploded";
$WEAPON_KILL[UT_MOD_SLAPPED] = "Bitchslapped";
$WEAPON_KILL[UT_MOD_NEGEV] = "Negev";
$WEAPON_KILL[UT_MOD_HK69_HIT] = "HK 69 hit";
$WEAPON_KILL[UT_MOD_M4] = "M4";
$WEAPON_KILL[UT_MOD_GLOCK] = "Glock";
$WEAPON_KILL[UT_MOD_COLT1911] = "Colt 1911";
$WEAPON_KILL[UT_MOD_MAC] = "Mac";
$WEAPON_KILL[UT_MOD_FLAG] = "Exploding flag";
$WEAPON_KILL[UT_MOD_GOOMBA] = "Curb Stomped";

// hit_weapon
define('HIT_UNKNOWN',0);
define('HIT_KNIFE',1);
define('HIT_BERETTA',2);
define('HIT_DEAGLE',3);
define('HIT_SPAS',4);
define('HIT_MP5K',5);
define('HIT_UMP45',6);
define('HIT_LR300',8);
define('HIT_G36',9);
define('HIT_PSG1',10);
define('HIT_SR8',14);
define('HIT_AK103',15);
define('HIT_NEGEV',17);
define('HIT_M4',19);
define('HIT_GLOCK',20);
define('HIT_COLT1911',21);
define('HIT_MAC',22);
define('HIT_KICKED',24);
define('HIT_KNIFE_THROWN',25);

$WEAPON_HIT[HIT_UNKNOWN] = "HIT_UNKNOWN";
$WEAPON_HIT[HIT_KNIFE] = "HIT_KNIFE";
$WEAPON_HIT[HIT_BERETTA] = "HIT_BERETTA";
$WEAPON_HIT[HIT_DEAGLE] = "HIT_DEAGLE";
$WEAPON_HIT[HIT_SPAS] = "HIT_SPAS";
$WEAPON_HIT[HIT_MP5K] = "HIT_MP5K";
$WEAPON_HIT[HIT_UMP45] = "HIT_UMP45";
$WEAPON_HIT[HIT_LR300] = "HIT_LR300";
$WEAPON_HIT[HIT_G36] = "HIT_G36";
$WEAPON_HIT[HIT_PSG1] = "HIT_PSG1";
$WEAPON_HIT[HIT_SR8] = "HIT_SR8";
$WEAPON_HIT[HIT_AK103] = "HIT_AK103";
$WEAPON_HIT[HIT_NEGEV] = "HIT_NEGEV";
$WEAPON_HIT[HIT_M4] = "HIT_M4";
$WEAPON_HIT[HIT_GLOCK] = "HIT_GLOCK";
$WEAPON_HIT[HIT_COLT1911] = "HIT_COLT1911";
$WEAPON_HIT[HIT_MAC] = "HIT_MAC";
$WEAPON_HIT[HIT_KICKED] = "HIT_KICKED";
$WEAPON_HIT[HIT_KNIFE_THROWN] = "HIT_KNIFE_THROWN";

// hit_part
define('HIT_HEAD', 1);
define('HIT_HELMET', 2);
define('HIT_TORSO', 3);
define('HIT_VEST', 4);
define('HIT_LEFT_ARM', 5);
define('HIT_RIGHT_ARM', 6);
define('HIT_GROIN', 7);
define('HIT_BUTT', 8);
define('HIT_LEFT_UPPER_LEG', 9);
define('HIT_RIGHT_UPPER_LEG', 10);
define('HIT_LEFT_LOWER_LEG', 11);
define('HIT_RIGHT_LOWER_LEG', 12);
define('HIT_LEFT_FOOT', 13);
define('HIT_RIGHT_FOOT', 14);

$BODY_PART[HIT_HEAD] = "HIT_HEAD";
$BODY_PART[HIT_HELMET] = "HIT_HELMET";
$BODY_PART[HIT_TORSO] = "HIT_TORSO";
$BODY_PART[HIT_VEST] = "HIT_VEST";
$BODY_PART[HIT_LEFT_ARM] = "HIT_LEFT_ARM";
$BODY_PART[HIT_RIGHT_ARM] = "HIT_RIGHT_ARM";
$BODY_PART[HIT_GROIN] = "HIT_GROIN";
$BODY_PART[HIT_BUTT] = "HIT_BUTT";
$BODY_PART[HIT_LEFT_UPPER_LEG] = "HIT_LEFT_UPPER_LEG";
$BODY_PART[HIT_RIGHT_UPPER_LEG] = "HIT_RIGHT_UPPER_LEG";
$BODY_PART[HIT_LEFT_LOWER_LEG] = "HIT_LEFT_LOWER_LEG";
$BODY_PART[HIT_RIGHT_LOWER_LEG] = "HIT_RIGHT_LOWER_LEG";
$BODY_PART[HIT_LEFT_FOOT] = "HIT_LEFT_FOOT";
$BODY_PART[HIT_RIGHT_FOOT] = "HIT_RIGHT_FOOT";
$HIT_PART = &$BODY_PART;

$WEAPON_DAMAGE[HIT_KNIFE][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_HELMET]				= 60;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_TORSO]				= 44;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_VEST]					= 35;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_LEFT_ARM]				= 20;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_RIGHT_ARM]			= 20;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_GROIN]				= 40;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_BUTT]					= 37;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_LEFT_UPPER_LEG]		= 20;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_RIGHT_UPPER_LEG]		= 20;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_LEFT_LOWER_LEG]		= 18;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_RIGHT_LOWER_LEG]		= 18;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_LEFT_FOOT]			= 15;
$WEAPON_DAMAGE[HIT_KNIFE][HIT_RIGHT_FOOT]			= 15;

$WEAPON_DAMAGE[HIT_KNIFE_THROWN] = &$WEAPON_DAMAGE[HIT_KNIFE];

$WEAPON_DAMAGE[HIT_BERETTA][HIT_HEAD]				= 100;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_HELMET]				= 34;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_TORSO]				= 33;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_VEST]				= 23;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_LEFT_ARM]			= 14;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_RIGHT_ARM]			= 14;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_GROIN]				= 28;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_BUTT]				= 25;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_LEFT_UPPER_LEG]		= 14;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_RIGHT_UPPER_LEG]	= 14;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_LEFT_LOWER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_RIGHT_LOWER_LEG]	= 17;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_LEFT_FOOT]			= 11;
$WEAPON_DAMAGE[HIT_BERETTA][HIT_RIGHT_FOOT]			= 11;

$WEAPON_DAMAGE[HIT_DEAGLE][HIT_HEAD]				= 100;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_HELMET]				= 66;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_TORSO]				= 44;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_VEST]				= 35;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_LEFT_ARM]			= 20;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_RIGHT_ARM]			= 20;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_GROIN]				= 40;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_BUTT]				= 41;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_LEFT_UPPER_LEG]		= 28;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_RIGHT_UPPER_LEG]		= 28;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_LEFT_LOWER_LEG]		= 22;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_RIGHT_LOWER_LEG]		= 22;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_LEFT_FOOT]			= 18;
$WEAPON_DAMAGE[HIT_DEAGLE][HIT_RIGHT_FOOT]			= 18;

$WEAPON_DAMAGE[HIT_SPAS][HIT_HEAD]					= 7;
$WEAPON_DAMAGE[HIT_SPAS][HIT_HELMET]				= 6;
$WEAPON_DAMAGE[HIT_SPAS][HIT_TORSO]					= 4;
$WEAPON_DAMAGE[HIT_SPAS][HIT_VEST]					= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_LEFT_ARM]				= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_RIGHT_ARM]				= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_GROIN]					= 3;
$WEAPON_DAMAGE[HIT_SPAS][HIT_BUTT]					= 3;
$WEAPON_DAMAGE[HIT_SPAS][HIT_LEFT_UPPER_LEG]		= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_RIGHT_UPPER_LEG]		= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_LEFT_LOWER_LEG]		= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_RIGHT_LOWER_LEG]		= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_LEFT_FOOT]				= 2;
$WEAPON_DAMAGE[HIT_SPAS][HIT_RIGHT_FOOT]			= 2;

$WEAPON_DAMAGE[HIT_MP5K][HIT_HEAD]					= 50;
$WEAPON_DAMAGE[HIT_MP5K][HIT_HELMET]				= 34;
$WEAPON_DAMAGE[HIT_MP5K][HIT_TORSO]					= 30;
$WEAPON_DAMAGE[HIT_MP5K][HIT_VEST]					= 20;
$WEAPON_DAMAGE[HIT_MP5K][HIT_LEFT_ARM]				= 11;
$WEAPON_DAMAGE[HIT_MP5K][HIT_RIGHT_ARM]				= 11;
$WEAPON_DAMAGE[HIT_MP5K][HIT_GROIN]					= 25;
$WEAPON_DAMAGE[HIT_MP5K][HIT_BUTT]					= 25;
$WEAPON_DAMAGE[HIT_MP5K][HIT_LEFT_UPPER_LEG]		= 15;
$WEAPON_DAMAGE[HIT_MP5K][HIT_RIGHT_UPPER_LEG]		= 15;
$WEAPON_DAMAGE[HIT_MP5K][HIT_LEFT_LOWER_LEG]		= 13;
$WEAPON_DAMAGE[HIT_MP5K][HIT_RIGHT_LOWER_LEG]		= 13;
$WEAPON_DAMAGE[HIT_MP5K][HIT_LEFT_FOOT]				= 11;
$WEAPON_DAMAGE[HIT_MP5K][HIT_RIGHT_FOOT]			= 11;

$WEAPON_DAMAGE[HIT_UMP45][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_UMP45][HIT_HELMET]				= 54;
$WEAPON_DAMAGE[HIT_UMP45][HIT_TORSO]				= 44;
$WEAPON_DAMAGE[HIT_UMP45][HIT_VEST]					= 29;
$WEAPON_DAMAGE[HIT_UMP45][HIT_LEFT_ARM]				= 17;
$WEAPON_DAMAGE[HIT_UMP45][HIT_RIGHT_ARM]			= 17;
$WEAPON_DAMAGE[HIT_UMP45][HIT_GROIN]				= 36;
$WEAPON_DAMAGE[HIT_UMP45][HIT_BUTT]					= 32;
$WEAPON_DAMAGE[HIT_UMP45][HIT_LEFT_UPPER_LEG]		= 21;
$WEAPON_DAMAGE[HIT_UMP45][HIT_RIGHT_UPPER_LEG]		= 21;
$WEAPON_DAMAGE[HIT_UMP45][HIT_LEFT_LOWER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_UMP45][HIT_RIGHT_LOWER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_UMP45][HIT_LEFT_FOOT]			= 14;
$WEAPON_DAMAGE[HIT_UMP45][HIT_RIGHT_FOOT]			= 14;

$WEAPON_DAMAGE[HIT_LR300][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_LR300][HIT_HELMET]				= 51;
$WEAPON_DAMAGE[HIT_LR300][HIT_TORSO]				= 44;
$WEAPON_DAMAGE[HIT_LR300][HIT_VEST]					= 29;
$WEAPON_DAMAGE[HIT_LR300][HIT_LEFT_ARM]				= 17;
$WEAPON_DAMAGE[HIT_LR300][HIT_RIGHT_ARM]			= 17;
$WEAPON_DAMAGE[HIT_LR300][HIT_GROIN]				= 37;
$WEAPON_DAMAGE[HIT_LR300][HIT_BUTT]					= 33;
$WEAPON_DAMAGE[HIT_LR300][HIT_LEFT_UPPER_LEG]		= 20;
$WEAPON_DAMAGE[HIT_LR300][HIT_RIGHT_UPPER_LEG]		= 20;
$WEAPON_DAMAGE[HIT_LR300][HIT_LEFT_LOWER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_LR300][HIT_RIGHT_LOWER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_LR300][HIT_LEFT_FOOT]			= 14;
$WEAPON_DAMAGE[HIT_LR300][HIT_RIGHT_FOOT]			= 14;

$WEAPON_DAMAGE[HIT_G36] = &$WEAPON_DAMAGE[HIT_LR300];
$WEAPON_DAMAGE[HIT_M4] = &$WEAPON_DAMAGE[HIT_LR300];

$WEAPON_DAMAGE[HIT_PSG1][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_PSG1][HIT_HELMET]				= 100;
$WEAPON_DAMAGE[HIT_PSG1][HIT_TORSO]					= 97;
$WEAPON_DAMAGE[HIT_PSG1][HIT_VEST]					= 63;
$WEAPON_DAMAGE[HIT_PSG1][HIT_LEFT_ARM]				= 36;
$WEAPON_DAMAGE[HIT_PSG1][HIT_RIGHT_ARM]				= 36;
$WEAPON_DAMAGE[HIT_PSG1][HIT_GROIN]					= 70;
$WEAPON_DAMAGE[HIT_PSG1][HIT_BUTT]					= 70;
$WEAPON_DAMAGE[HIT_PSG1][HIT_LEFT_UPPER_LEG]		= 41;
$WEAPON_DAMAGE[HIT_PSG1][HIT_RIGHT_UPPER_LEG]		= 41;
$WEAPON_DAMAGE[HIT_PSG1][HIT_LEFT_LOWER_LEG]		= 36;
$WEAPON_DAMAGE[HIT_PSG1][HIT_RIGHT_LOWER_LEG]		= 36;
$WEAPON_DAMAGE[HIT_PSG1][HIT_LEFT_FOOT]				= 29;
$WEAPON_DAMAGE[HIT_PSG1][HIT_RIGHT_FOOT]			= 29;

$WEAPON_DAMAGE[HIT_SR8][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_SR8][HIT_HELMET]					= 100;
$WEAPON_DAMAGE[HIT_SR8][HIT_TORSO]					= 100;
$WEAPON_DAMAGE[HIT_SR8][HIT_VEST]					= 100;
$WEAPON_DAMAGE[HIT_SR8][HIT_LEFT_ARM]				= 50;
$WEAPON_DAMAGE[HIT_SR8][HIT_RIGHT_ARM]				= 50;
$WEAPON_DAMAGE[HIT_SR8][HIT_GROIN]					= 97;
$WEAPON_DAMAGE[HIT_SR8][HIT_BUTT]					= 90;
$WEAPON_DAMAGE[HIT_SR8][HIT_LEFT_UPPER_LEG]			= 60;
$WEAPON_DAMAGE[HIT_SR8][HIT_RIGHT_UPPER_LEG]		= 60;
$WEAPON_DAMAGE[HIT_SR8][HIT_LEFT_LOWER_LEG]			= 50;
$WEAPON_DAMAGE[HIT_SR8][HIT_RIGHT_LOWER_LEG]		= 50;
$WEAPON_DAMAGE[HIT_SR8][HIT_LEFT_FOOT]				= 40;
$WEAPON_DAMAGE[HIT_SR8][HIT_RIGHT_FOOT]				= 40;

$WEAPON_DAMAGE[HIT_AK103][HIT_HEAD]					= 100;
$WEAPON_DAMAGE[HIT_AK103][HIT_HELMET]				= 58;
$WEAPON_DAMAGE[HIT_AK103][HIT_TORSO]				= 51;
$WEAPON_DAMAGE[HIT_AK103][HIT_VEST]					= 34;
$WEAPON_DAMAGE[HIT_AK103][HIT_LEFT_ARM]				= 19;
$WEAPON_DAMAGE[HIT_AK103][HIT_RIGHT_ARM]			= 19;
$WEAPON_DAMAGE[HIT_AK103][HIT_GROIN]				= 41;
$WEAPON_DAMAGE[HIT_AK103][HIT_BUTT]					= 32;
$WEAPON_DAMAGE[HIT_AK103][HIT_LEFT_UPPER_LEG]		= 22;
$WEAPON_DAMAGE[HIT_AK103][HIT_RIGHT_UPPER_LEG]		= 22;
$WEAPON_DAMAGE[HIT_AK103][HIT_LEFT_LOWER_LEG]		= 19;
$WEAPON_DAMAGE[HIT_AK103][HIT_RIGHT_LOWER_LEG]		= 19;
$WEAPON_DAMAGE[HIT_AK103][HIT_LEFT_FOOT]			= 15;
$WEAPON_DAMAGE[HIT_AK103][HIT_RIGHT_FOOT]			= 15;

$WEAPON_DAMAGE[HIT_GLOCK][HIT_HEAD]					= 60;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_HELMET]				= 40;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_TORSO]				= 33;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_VEST]					= 23;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_LEFT_ARM]				= 14;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_RIGHT_ARM]			= 14;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_GROIN]				= 28;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_BUTT]					= 25;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_LEFT_UPPER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_RIGHT_UPPER_LEG]		= 17;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_LEFT_LOWER_LEG]		= 14;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_RIGHT_LOWER_LEG]		= 14;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_LEFT_FOOT]			= 11;
$WEAPON_DAMAGE[HIT_GLOCK][HIT_RIGHT_FOOT]			= 11;

$WEAPON_DAMAGE[HIT_NEGEV][HIT_HEAD]					= 50;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_HELMET]				= 34;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_TORSO]				= 30;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_VEST]					= 20;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_LEFT_ARM]				= 11;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_RIGHT_ARM]			= 11;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_GROIN]				= 25;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_BUTT]					= 22;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_LEFT_UPPER_LEG]		= 13;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_RIGHT_UPPER_LEG]		= 13;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_LEFT_LOWER_LEG]		= 11;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_RIGHT_LOWER_LEG]		= 11;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_LEFT_FOOT]			= 9;
$WEAPON_DAMAGE[HIT_NEGEV][HIT_RIGHT_FOOT]			= 9;

$WEAPON_DAMAGE[HIT_COLT1911][HIT_HEAD]				= 100;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_HELMET]			= 60;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_TORSO]				= 37;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_VEST]				= 27;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_LEFT_ARM]			= 15;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_RIGHT_ARM]			= 15;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_GROIN]				= 32;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_BUTT]				= 29;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_LEFT_UPPER_LEG]	= 22;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_RIGHT_UPPER_LEG]	= 22;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_LEFT_LOWER_LEG]	= 15;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_RIGHT_LOWER_LEG]	= 15;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_LEFT_FOOT]			= 11;
$WEAPON_DAMAGE[HIT_COLT1911][HIT_RIGHT_FOOT]		= 11;

$WEAPON_DAMAGE[HIT_MAC][HIT_HEAD]					= 34;
$WEAPON_DAMAGE[HIT_MAC][HIT_HELMET]					= 29;
$WEAPON_DAMAGE[HIT_MAC][HIT_TORSO]					= 20;
$WEAPON_DAMAGE[HIT_MAC][HIT_VEST]					= 15;
$WEAPON_DAMAGE[HIT_MAC][HIT_LEFT_ARM]				= 11;
$WEAPON_DAMAGE[HIT_MAC][HIT_RIGHT_ARM]				= 11;
$WEAPON_DAMAGE[HIT_MAC][HIT_GROIN]					= 18;
$WEAPON_DAMAGE[HIT_MAC][HIT_BUTT]					= 17;
$WEAPON_DAMAGE[HIT_MAC][HIT_LEFT_UPPER_LEG]			= 15;
$WEAPON_DAMAGE[HIT_MAC][HIT_RIGHT_UPPER_LEG]		= 15;
$WEAPON_DAMAGE[HIT_MAC][HIT_LEFT_LOWER_LEG]			= 13;
$WEAPON_DAMAGE[HIT_MAC][HIT_RIGHT_LOWER_LEG]		= 13;
$WEAPON_DAMAGE[HIT_MAC][HIT_LEFT_FOOT]				= 11;
$WEAPON_DAMAGE[HIT_MAC][HIT_RIGHT_FOOT]				= 11;

define('TEAM_FFA', 0);
define('TEAM_RED', 1);
define('TEAM_BLUE', 2);
define('TEAM_SPEC', 3);

$TEAM[TEAM_FFA] = "TEAM_FFA";
$TEAM[TEAM_RED] = "TEAM_RED";
$TEAM[TEAM_BLUE] = "TEAM_BLUE";
$TEAM[TEAM_SPEC] = "TEAM_SPEC";
?>