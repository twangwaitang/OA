-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-07-07 09:11:08
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oa`
--

-- --------------------------------------------------------

--
-- 表的结构 `advisor`
--

CREATE TABLE `advisor` (
  `id` int(11) NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gendar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `face` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_top` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `advisor`
--

INSERT INTO `advisor` (`id`, `number`, `name`, `gendar`, `tel`, `roles`, `created_time`, `updated_time`, `position`, `face`, `info`, `is_top`) VALUES
(2, '5555', '王小泉', '男', '55555555555', '["ROLE_ADVISOR"]', '2017-05-04 07:35:05', '2017-05-04 07:35:05', '', '', '', 1),
(3, '44444', '4444444', '男', '44444444444', '["ROLE_ADVISOR"]', '2017-05-04 09:49:54', '2017-05-04 09:49:54', NULL, NULL, NULL, 0),
(4, '111222', '11111111', '男', '11111111111', '["ROLE_ADVISOR"]', '2017-06-09 11:38:54', '2017-06-09 11:38:54', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `advisors_stgroups`
--

CREATE TABLE `advisors_stgroups` (
  `advisor_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `advisors_stgroups`
--

INSERT INTO `advisors_stgroups` (`advisor_id`, `group_id`) VALUES
(2, 1),
(2, 3);

-- --------------------------------------------------------

--
-- 表的结构 `assignment`
--

CREATE TABLE `assignment` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_readed` tinyint(1) DEFAULT '0',
  `is_finished` tinyint(1) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `assignment`
--

INSERT INTO `assignment` (`id`, `teacher_id`, `content`, `is_readed`, `is_finished`, `created_time`, `updated_time`, `title`) VALUES
(1, 3, '<p>111</p>\n', NULL, NULL, '2017-06-27 16:28:57', '2017-06-27 16:28:57', '任务测试');

-- --------------------------------------------------------

--
-- 表的结构 `assignment_replay`
--

CREATE TABLE `assignment_replay` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `assignment_replay`
--

INSERT INTO `assignment_replay` (`id`, `user_id`, `assignment_id`, `content`, `created_time`, `updated_time`) VALUES
(4, 42, 8, '<p>已经收到</p>\r\n', '2017-07-02 17:19:27', '2017-07-02 17:19:27'),
(5, 42, 8, '<p>再次测试</p>\n', '2017-07-02 17:34:23', '2017-07-02 17:34:23'),
(6, 56, 8, '<p>老师测试</p>\n', '2017-07-04 15:06:42', '2017-07-04 15:06:42'),
(7, 42, 8, '<p>好的，老师</p>\n', '2017-07-04 15:18:35', '2017-07-04 15:18:35'),
(11, 56, 10, '<p>nihao</p>\n', '2017-07-05 10:12:29', '2017-07-05 10:12:29'),
(12, 56, 9, '<p>nihao</p>\n', '2017-07-05 10:12:45', '2017-07-05 10:12:45'),
(13, 56, 9, '<p>2</p>\n', '2017-07-05 10:29:32', '2017-07-05 10:29:32'),
(14, 56, 8, '<p>回复</p>\n', '2017-07-05 10:56:12', '2017-07-05 10:56:12'),
(15, 42, 8, '<p>学生在此回复</p>\n', '2017-07-05 11:01:52', '2017-07-05 11:01:52');

-- --------------------------------------------------------

--
-- 表的结构 `base_web`
--

CREATE TABLE `base_web` (
  `id` int(11) NOT NULL,
  `web_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_open` tinyint(1) DEFAULT '1',
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `footer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `base_web`
--

INSERT INTO `base_web` (`id`, `web_title`, `is_open`, `logo`, `footer`, `created_time`, `updated_time`) VALUES
(1, '堂外堂信息可以有限公司', 1, '5cf92045b093c1e4f4a9ba3ef42be3bf.png', '页面尾部信息', '2017-07-07 14:27:40', '2017-07-07 14:27:40');

-- --------------------------------------------------------

--
-- 表的结构 `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_good` tinyint(1) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `blog`
--

INSERT INTO `blog` (`id`, `student_id`, `content`, `is_good`, `created_time`, `updated_time`, `title`) VALUES
(5, 292, '<p>neirong</p>\n', NULL, '2017-06-06 21:17:40', '2017-06-06 21:17:40', 'rizhi'),
(6, 292, '<p>blog内容</p>\n', NULL, '2017-06-06 21:19:02', '2017-06-06 21:19:02', 'blog'),
(7, 292, '<p>一、基础理论<br />\n1．有机整体的中心是：五脏。2．有机整体的主宰是：心。3．阳中之阳的时间是：上午。4．阴中之阴的时间是：前半夜。5．阳中之阴的时间是：下午。6．阳中之阳的时间是：上午。7．说明阴阳互根的是：阴在内，阳之守也。8．说明对立制约的是：动极者镇之以静。9．说明阴阳互根的是：阴中求阳。10．说明阴阳转化的是：热极生寒。11．称&ldquo;阳中之阳&rdquo;的脏是：心。12．称&ldquo;阴中之阳&rdquo;的脏是：肝。13．称&ldquo;阴中之阴&rdquo;的脏是：肾。14．称&ldquo;阳中之阴&rdquo;的脏是：肺。15．阴偏胜所致证候是：实寒证。16．阴偏衰所致证候是：虚热证。17．阳偏胜所致证候是：实热证。18．阳偏衰所致证候是：虚寒证。19．根据阴阳互根确定的治法是：阳中求阴。20．适用于阳偏衰的治法是：阴病治阳。21．根据阴阳对立制约确定的治法是：热者寒之。22．适用于阳偏胜的治法是：热者寒之。23．&ldquo;木&rdquo;的特性是：曲直。24．&ldquo;水&rdquo;的特性是：润下。25．属于&ldquo;金&rdquo;的音是：商音。26．属于&ldquo;水&rdquo;的音是：羽音。27．木的&ldquo;所不胜&rdquo;之行是：金。28．水的&ldquo;所胜&rdquo;之行是：火。29．金的子行为：水。30．火的母行为：木。31．木的&ldquo;所不胜&rdquo;之&ldquo;子&rdquo;是：水。32．木的&ldquo;母&rdquo;之&ldquo;所胜&rdquo;是：火。33．肝病传脾的是：相乘。34．肝病传心的是：母病及子。35．肺病及肾是：母病及子。36．木火刑金是：相侮。37．&ldquo;见肝之病，知肝传脾&rdquo;属于：相乘。38．&ldquo;水气凌心&rdquo;属于：相乘。39．喜胜：悲。40．恐胜：喜。41．属于&ldquo;火&rdquo;的是：喜。42．属于&ldquo;金&rdquo;的是：悲。43．属于&ldquo;水&rdquo;的是：耳。44．属于&ldquo;土&rdquo;的是：口。45．属于&ldquo;水&rdquo;的是：咸。46．属于&ldquo;木&rdquo;的是：酸。47．属于&ldquo;水&rdquo;的是：黑。48．属于&ldquo;金&rdquo;的是：白。49．&ldquo;君主之官&rdquo;指：心。50．&ldquo;将军之官&rdquo;指：肝。51．&ldquo;生之本&rdquo;指：心。52．&ldquo;罢极之本&rdquo;指：肝。53．&ldquo;气之根&rdquo;是指：肾。54．&ldquo;气之主&rdquo;是指：肺。55．&ldquo;先天之本&rdquo;是指：肾。56．&ldquo;后天之本&rdquo;是指：脾。57．&ldquo;生气之源&rdquo;指：脾。58．&ldquo;主气之枢&rdquo;指：肺。59．&ldquo;生痰之源&rdquo;指：脾。60．&ldquo;贮痰之器&rdquo;指：肺。61．主行血的是：心。62．主统血的是：脾。63．朝百脉的是：肺。64．主生血的是：脾。65．通调水道的是：肺。66．运化水液的是：脾。67．主行血的是：心。68．主藏血的是：肝。69．司呼吸的是：脾。70．主纳气的是：肾。71．主疏泄的是：肝。72．主闭藏的是：肾。73．气血生化之源是：脾。74．五脏六腑之大主是：心。75．主血的是：心。76．主气的是：脾。77．藏神的是：心。78．调畅情志的是：肝。79．主治节的是：肺。80．主升清的是：脾。81．与水液代谢关系最密切的是：肾。82．与血液运行关系最密切的是：心。83．心的功能为：行血。84．肝的功能为：藏血。85．肾的功能为：纳气。86．肺的功能为：主气。87．称为刚脏的是：肝。88．称为娇脏的是：肺。89．&ldquo;中正之官&rdquo;指：胆。90．&ldquo;受盛之官&rdquo;指：小肠。91．&ldquo;主津&rdquo;的是：大肠。92．&ldquo;主液&rdquo;的是：小肠。93．主受纳的是：胃。94．主化物的是：小肠。95．&ldquo;水谷之海&rdquo;指：胃。96．&ldquo;州都之官&rdquo;指：膀胱。97．有&ldquo;精血同源&rdquo;关系的是：肝肾。98．气机升降之枢是：脾胃。99．有&ldquo;水火既济&rdquo;关系的是：心肾。100．与气机调节关系密切的是：肝肺。1．&ldquo;髓海&rdquo;指：脑。2．&ldquo;血府&rdquo;指：脉。3．肺在体为：皮。4．肝在体为：筋。5．肾在体为：骨。6．心在体为：脉。7．心在志为：喜。8．肾在志为：恐。9．肝在志为：怒。10．脾在志为：思。11．脾的华为：唇。12．肾的华为：发。13．心的华为：面。14．肝的华为：爪。15．肝在窍为：目。16．肾在窍为：耳。17．与生长发育有关的是：推动作用。18．与血液运行有关的是：推动作用。19．精血转化依靠气的：气化作用。20．津液运行依靠气的：推动作用。21．肾所摄纳之气是指：清气。22．三焦所通行之气是指：元气。23．脉内的气是指：营气。24．脉外之气是指：卫气。25．宗气是：积于胸中之气。26．卫气是：行于脉外之气。27．脾肺共同化生的气是：宗气。28．肺所宣发的气是：卫气。29．生化血液的气是：营气。30．推动心脏搏动的气是：宗气。31．贯心脉的气是：宗气。32．推动生长发育的气是：元气。33．治疗血虚时配伍补气药的理论基础是：气能生血。34．&ldquo;气随血脱&rdquo;的理论基础是：血能载气。35．&ldquo;夺血者无汗&rdquo;的理论基础是：津血同源。36．&ldquo;吐下之余，定无完气&rdquo;的理论基础是：津能载气。37．分布于下肢内侧后缘的是：足少阴肾经。38．分布于下肢外侧后缘的是：足太阳膀胱经。39．分布于下肢内侧前缘的是：足太阴脾经。40．分布于下肢内侧中线的是：足厥阴肝经。41．足三阳经的走向是：从头走足。42．手三阳经的走向是：从手走头。43．循行于上肢内侧后缘的经脉是：手少阴心经。44．循行于上肢内侧前缘的经脉是：手太阴肺经。45．循行于下肢外侧后缘的经脉是：足太阳膀胱经。46．循行于下肢内侧中线的经脉是：足厥阴肝经。47．起于中焦的经脉是：手太阴肺经。48．起于目外眦的经脉是：足少阳胆经。49．别络的生理功能为：加强了十二经脉中相为表里的两经在四肢的联系。50．经别的生理功能为：加强了十二经脉中相为表里的两经在体内的联系。51．冲脉的功能是：调节十二经脉的气血。52．跷脉的功能是：分主一身左右之阴阳。53．太阳经病可见：头项痛。54．厥阴经病可见：巅顶痛。55．&ldquo;十二经脉之海&rdquo;是指：冲脉。56．约束纵行诸经的是：带脉。57．&ldquo;阳脉之海&rdquo;是：督脉。58．&ldquo;阴脉之海&rdquo;是：任脉。59．最易导致&ldquo;行痹&rdquo;的邪气是：风邪。60．最易导致&ldquo;着痹&rdquo;的邪气是：湿邪。61．火热之邪致病可见：狂躁妄动。62．湿邪致病可见：四肢困倦，胸闷呕恶。63．其性凝滞者为：寒邪。64．其性粘滞者为：湿邪。65．致病后常先困脾的邪气是：湿邪。66．最易伤肺的邪气是：燥邪。67．寒邪的致病特点是：易伤阳气。68．燥邪的性质与致病特点是：易于伤肺。69．风邪的性质与致病特点是：开泄。70．寒邪的性质与致病特点是：凝滞。71．其性收引的邪气是：寒邪。72．其性升散的邪气是：暑邪。73．易袭阴位的邪气是：湿邪。74．易袭阳位的邪气是：风邪。75．情志为病，过喜则：气缓。76．情志为病，过悲则：气消。77．过度悲伤可引起：精神萎靡不振，气短乏力。78．暴喜可引起：精神不集中，甚则失神狂乱。79．根据《素问．生气通天论》和《素问．五脏生成篇》，多食咸可导致：肾盛乘心。80．根据《素问．生气通天论》和《素问．五脏生成篇》，多食辛可导致：肺盛乘肝。81．可损伤心脾的因素是：劳神过度。82．可损伤脾胃的因素是：过饱。83．疾病发生的重要条件是：邪气。84．疾病发生的内部因素是：正气不足。85．正气不足，邪气亢盛的病证是：虚实夹杂证。86．邪气亢盛，正气不衰的证候是：实证。87．正气不足，邪气已尽，所形成的是：虚证。88．实邪结聚，阻滞经络，气血不能外达所形成的是：真实假虚证。89．阴盛格阳证属于：真寒假热证。90．阳盛格阴证属于：真热假寒证。91．阳虚则寒出现：虚寒证。92．阳胜则热出现：实热证。93．阴虚则热可引起：虚热证。94．阴胜则寒可引起：实寒证。95．外感寒邪的病机是：阴偏胜。96．过食生冷的病机是：阴偏胜。97．邪热内盛可出现：阳盛格阴。98．阴寒内盛可出现：阴胜则寒。99．&ldquo;阴胜则阳病&rdquo;的含义是：阴邪为病，阳气受损。200．&ldquo;阳胜则阴病&rdquo;的含义是：阳热亢盛，阴液受损。1．阴液不足，不能制阳为：阴虚则热。2．阳邪致病，导致阳偏盛为：阳胜则热。3．阳偏盛所导致的证候是：实热证。4．阳偏衰所导致的证候是：虚寒证。5．阴偏衰所形成的证候是：虚热证。6．阴偏盛所形成的证候是：实寒证。7．气脱病变，常见：汗出不止。8．气滞病变，常见：闷胀疼痛。9．气闭可见：突然昏厥，不省人事。10．气滞可见：闷胀疼痛。11．气升举无力的病变是：气陷。12．脏腑功能低下或衰退，多形成哪种证候：气虚。13．气脱属：气的出入异常。14．气闭属：气的出入异常。15．气闭或气脱的病机，主要是指：气的出入异常，或闭阻，或外散。16．气陷病机，主要是指：气虚无力升举，脏腑位置下垂。17．血热是指：血分有热，血行加速或迫血亡行。18．血瘀是指：血液循行迟缓或不畅或瘀阻停滞。19．血虚是指：血液不足或濡养功能减退。21．气滞可见：胸胁胀满疼痛。22．气不摄血可见：面色无华，疲乏无力，便血，皮下出血。23．反治属于：治病求本。24．用寒远寒属于：因时制宜。25．正治属于：治病求本。26．既病防变属于：治未病。27．虚人感冒应选用的方法是：标本同治。28．二便不利应选用的方法是：急则治其标。29．寒因寒用属于：反治。30．实则泻之属于：正治。31．热因热用属于：反治。32．热者寒之属于：正治。33．扶正法适用于：虚证。34．祛邪法适用于：实证。</p>\n', 1, '2017-06-07 14:44:24', '2017-06-07 14:44:24', '一篇价值过百万的中医日志，请把它背下来');

-- --------------------------------------------------------

--
-- 表的结构 `chapter`
--

CREATE TABLE `chapter` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `chapter`
--

INSERT INTO `chapter` (`id`, `course_id`, `name`, `created_time`, `updated_time`) VALUES
(1, 15, '中医内科疾病发病学要点', '2017-05-15 21:31:49', '2017-05-15 21:31:49'),
(3, 15, '中医内科学发展简史', '2017-05-15 22:00:17', '2017-05-15 22:00:17'),
(4, 13, '中药学', '2017-05-16 22:05:31', '2017-05-16 22:05:31');

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `lesson_id`, `content`, `created_time`, `updated_time`, `comment_id`, `course_id`) VALUES
(12, 55, 40, '我来问个问题', '2017-05-31 22:49:20', '2017-05-31 22:49:20', NULL, 15),
(13, 55, 40, '继续发文', '2017-05-31 23:02:10', '2017-05-31 23:02:10', NULL, 13),
(14, 55, 40, '再来', '2017-05-31 23:02:23', '2017-05-31 23:02:23', NULL, 15),
(16, 55, 40, '我来回复', '2017-06-01 09:18:54', '2017-06-01 09:18:54', 12, 15),
(17, 42, 40, '提问', '2017-06-02 17:14:57', '2017-06-02 17:14:57', NULL, 15),
(18, 42, 40, '来什么', '2017-06-02 17:16:11', '2017-06-02 17:16:11', 14, 15);

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `course_plan` longtext COLLATE utf8_unicode_ci,
  `course_goal` longtext COLLATE utf8_unicode_ci,
  `course_info` longtext COLLATE utf8_unicode_ci,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `teach_hours` int(11) NOT NULL,
  `thumbnial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_public` smallint(6) NOT NULL,
  `is_finished` smallint(6) NOT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `course`
--

INSERT INTO `course` (`id`, `name`, `course_plan`, `course_goal`, `course_info`, `created_time`, `updated_time`, `teach_hours`, `thumbnial`, `is_public`, `is_finished`, `group_id`) VALUES
(13, '中医学', '<p>11</p>\n', '<p>2</p>\n', '<p>2</p>\n', '2017-05-13 19:41:18', '2017-05-13 19:41:18', 32, '64aff3d36d797b2dbcdbb307b3ecb7ac.jpeg', 0, 0, 1),
(15, '中医内科学', '', '<p>中医基础理论，中药来源、产地、炮制、功效及常用中药和方剂的使用，针灸方法、推拿方法、饮食养生保健知识等。</p>\n', '<p>将理论教学和形式多样的实训教学相结合，基本理论和基础知识以&ldquo;以必需和够用&rdquo;为度，强调基本技能的培养，并注重培养学生的综合素质、自学能力和评判性思维即分析问题、解决问题的能力</p>\n', '2017-05-14 20:57:58', '2017-05-14 20:57:58', 12, '286f69ce12950e5e176e8d47c9448866.jpeg', 1, 1, 3),
(16, '课程三', '', '', '', '2017-06-07 22:19:52', '2017-06-07 22:19:52', 11, '93e388908ad524e7db077ffc5b66d2d2.jpeg', 0, 0, 4),
(17, '课程四', '', '', '', '2017-06-07 22:20:36', '2017-06-07 22:20:36', 10, '0962aeeeee6a427d47728f744d3a6449.jpeg', 0, 0, 5),
(18, '课程五', '', '', '', '2017-06-07 22:21:10', '2017-06-07 22:21:10', 20, '9451e2c544777ae6d2fe5f2545610826.jpeg', 0, 0, 6);

-- --------------------------------------------------------

--
-- 表的结构 `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_started` tinyint(1) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `evaluation`
--

INSERT INTO `evaluation` (`id`, `title`, `is_started`, `created_time`, `updated_time`) VALUES
(1, '2017(2)', 1, '2017-07-05 16:14:26', '2017-07-05 16:14:26'),
(2, '2017-1', 1, '2017-07-06 11:21:42', '2017-07-06 11:21:42');

-- --------------------------------------------------------

--
-- 表的结构 `evaluation_detail`
--

CREATE TABLE `evaluation_detail` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `evaluation_id` int(11) DEFAULT NULL,
  `score` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `evaluation_detail`
--

INSERT INTO `evaluation_detail` (`id`, `course_id`, `user_id`, `evaluation_id`, `score`, `created_time`, `updated_time`, `student_id`) VALUES
(7, 13, 55, 2, '50', '2017-07-06 16:03:08', '2017-07-06 16:03:08', 292),
(8, 13, 56, 2, '50', '2017-07-06 22:44:04', '2017-07-06 22:44:04', 292),
(9, 15, 56, 2, '50', '2017-07-06 22:49:56', '2017-07-06 22:49:56', 292),
(10, 13, 65, 2, '70', '2017-07-06 22:56:51', '2017-07-06 22:56:51', 292),
(11, 15, 65, 2, '50', '2017-07-06 22:57:06', '2017-07-06 22:57:06', 292);

-- --------------------------------------------------------

--
-- 表的结构 `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` longtext COLLATE utf8_unicode_ci NOT NULL,
  `score_rate` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `start_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `exam`
--

INSERT INTO `exam` (`id`, `course_id`, `title`, `info`, `score_rate`, `start_time`, `end_time`, `created_time`, `updated_time`, `duration`) VALUES
(1, 15, '期末考试', '<p><span style="color:#e74c3c">考试须知</span></p>\n', 'a:3:{i:0;s:2:"50";i:1;s:2:"50";i:2;s:0:"";}', '2017/06/16 00:00:00', '2017/06/27 00:00:00', '2017-06-12 17:31:56', '2017-06-12 17:31:56', 60),
(2, 15, '111', '<p>11111</p>\n', 'a:3:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";}', '2017/06/15 00:00:00', '2017/06/15 12:00:00', '2017-06-15 15:17:21', '2017-06-15 15:17:21', 1111);

-- --------------------------------------------------------

--
-- 表的结构 `exam_question`
--

CREATE TABLE `exam_question` (
  `questions_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `exam_question`
--

INSERT INTO `exam_question` (`questions_id`, `exam_id`) VALUES
(6, 1),
(9, 1),
(10, 1),
(12, 1);

-- --------------------------------------------------------

--
-- 表的结构 `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lesson_res` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `lesson`
--

INSERT INTO `lesson` (`id`, `chapter_id`, `name`, `created_time`, `updated_time`, `video`, `duration`, `lesson_res`) VALUES
(2, 3, '气一元论', '2017-05-16 07:26:35', '2017-05-16 07:26:35', 'f43c4e342115066448b4908e26882d06.mp4', '368', NULL),
(40, 1, '中医学理论体系的形成和发展', '2017-05-16 21:02:56', '2017-05-16 21:02:56', '24b9258120af1269625dc7ef28e89545.mp4', '368', '<p><a href="http://www.baidu.com" target="_blank">百度</a></p>\n'),
(41, 1, '中医学理论体系的组成', '2017-05-16 21:02:56', '2017-05-16 21:02:56', '949a847ff3f2b8ac49cf5400ec5d3760.mp4', '1440', NULL),
(42, 1, '中医学的哲学基础', '2017-05-16 21:03:28', '2017-05-16 21:03:28', 'bc4cc34e6daf0328b92eb274bb79490d.mp4', '10', NULL),
(44, 3, '阴阳学说', '2017-05-16 21:04:39', '2017-05-16 21:04:39', '408643d4f837163fac99f517a16bd23c.mp4', '69', NULL),
(45, 3, '五行学说', '2017-05-16 21:04:39', '2017-05-16 21:04:39', '', NULL, NULL),
(46, 3, '气一元论、阴阳学说、五行学说的关系', '2017-05-16 21:05:31', '2017-05-16 21:05:31', '', NULL, NULL),
(51, 4, '中药学历史', '2017-05-16 22:05:47', '2017-05-16 22:05:47', 'a50e436ddf68cc13ecd99ffca5d33a9b.mp4', '65', NULL),
(52, 1, '中医历史简介', '2017-05-27 12:10:30', '2017-05-27 12:10:30', NULL, NULL, NULL),
(53, 1, '中医的价值', '2017-05-27 12:14:12', '2017-05-27 12:14:12', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `lesson_question`
--

CREATE TABLE `lesson_question` (
  `questions_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `lesson_question`
--

INSERT INTO `lesson_question` (`questions_id`, `lesson_id`) VALUES
(9, 40),
(10, 40),
(12, 40);

-- --------------------------------------------------------

--
-- 表的结构 `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_public` tinyint(1) DEFAULT '0',
  `thumbnial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `links`
--

INSERT INTO `links` (`id`, `title`, `is_public`, `thumbnial`, `url`, `created_time`, `updated_time`) VALUES
(2, '友情链接测试', 1, 'c0d7403b830a876c76aa00fbc0a72b4b.jpeg', 'http://www.baidu.com', '2017-07-07 11:23:46', '2017-07-07 11:23:46'),
(3, '堂外堂信息科技有限公司', 1, '3b8981ff67ef52a1da86e603f4235a2a.png', 'http://www.baidu.com', '2017-07-07 11:38:21', '2017-07-07 11:38:21');

-- --------------------------------------------------------

--
-- 表的结构 `makers`
--

CREATE TABLE `makers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `maker_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `makers`
--

INSERT INTO `makers` (`id`, `question_id`, `lesson_id`, `maker_time`, `created_time`, `updated_time`) VALUES
(11, 6, 42, '2', '2017-06-09 15:10:44', '2017-06-09 15:10:44'),
(12, 9, 42, '5', '2017-06-09 15:10:44', '2017-06-09 15:10:44');

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_top` tinyint(1) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `type`, `is_top`, `created_time`, `updated_time`) VALUES
(1, '中医最新发展', '<p>111</p>\n', '资讯', 1, '2017-06-07 19:51:03', '2017-06-07 19:51:03'),
(2, '6月份通知', '<p>33333</p>\n', '通知', 1, '2017-06-07 19:53:36', '2017-06-07 19:53:36'),
(3, '课程最新通知', '<p>11111111111</p>\n', '通知', 1, '2017-06-07 19:55:18', '2017-06-07 19:55:18'),
(5, '为什么要学习中医', '<p>去</p>\n', '资讯', 1, '2017-06-07 21:26:08', '2017-06-07 21:26:08'),
(6, '中医治疗痛风', '<p>中医技术博大精深,讲究辨证施治,且因人因病而治,方法各不相同。当前关于痛风中医治疗依然成为主流方式,由于其安全,高效,无毒副成为众多患者的首选</p>\n\n<p><img src="https://imgsa.baidu.com/exp/pic/item/9f1011b30f2442a70acb3ec4d043ad4bd013024f.jpg" style="height:298px; width:443px" /></p>\n\n<p><strong>方法/步骤</strong></p>\n\n<p>主要可从痛风病发的不同阶段及症状表现入手</p>\n\n<ol>\n	<li>\n	<p>其一、急性发作期:病人发热、头痛、关节明显红肿、胀痛,证属风湿热痹。治宜清热利湿、祛风通络。方用四妙散加味汤</p>\n\n	<p>&nbsp;</p>\n\n	<p><img src="https://imgsa.baidu.com/exp/pic/item/bd7faf3533fa828b3d525e82fc1f4134970a5a1b.jpg" style="height:285px; width:441px" /></p>\n	</li>\n	<li>\n	<p>其二、真寒假热型:关节红肿、疼痛,口渴不欲饮,苔白兼黄,脉洪无力。方用六味地黄汤,以滋阴补肾、清利湿热;加桂枝、刨附片以温经通脉散寒;加木瓜、川牛膝以活血舒筋通络佐以引药下行。</p>\n\n	<p><img src="https://imgsa.baidu.com/exp/pic/item/4e0b3ea4462309f7b72a6e73730e0cf3d6cad6cf.jpg" style="height:292px; width:443px" /></p>\n	</li>\n	<li>\n	<p>其三、慢性期:关节疼痛,反复发作,灼热明显减轻,关节僵硬、畸形,活动受限。治宜调理气血,补益肝肾,酌加通经活络、活血化瘀疗法,方用黄芪桂枝五物汤加味。</p>\n\n	<p><img src="https://imgsa.baidu.com/exp/pic/item/5beeba0f4bfbfbed7553f51e79f0f736afc31f0e.jpg" style="height:292px; width:314px" /></p>\n	</li>\n	<li>\n	<p>其四、痛风石瘘证:其属久病气衰,阴寒内积、寒阻血凝、肌肤失养、破溃成瘘。治以济生肾气丸内服,每次1丸,每日2次,外敷回阳玉龙膏,以暖血生肌;以干姜、肉桂、草乌、南星化寒痰,活死肌;以赤芍、白芷散滞血,生肌肉。</p>\n\n	<p><img src="https://imgsa.baidu.com/exp/pic/item/c28fddfdfc0392456ea8226e8694a4c27d1e2577.jpg" style="height:294px; width:431px" /></p>\n	</li>\n</ol>\n\n<p><strong>注意事项</strong></p>\n\n<p>中医治疗痛风南北差异较大,其原因在于病发人群及地域发展水平等都有关系。所以患者如接受专业中医治疗可就自身地理位置而定,上海,北京两地为典型代表为大家推荐。</p>\n', '资讯', 1, '2017-06-08 07:33:01', '2017-06-08 07:33:01'),
(7, '有一个通知', '<p>通知内容测试</p>\n', '通知', 1, '2017-06-08 07:35:34', '2017-06-08 07:35:34');

-- --------------------------------------------------------

--
-- 表的结构 `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_good` int(11) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `lesson_id`, `content`, `is_good`, `created_time`, `updated_time`, `course_id`) VALUES
(1, 42, 40, '111', NULL, '2017-06-01 16:23:58', '2017-06-01 16:23:58', 15),
(2, 42, 40, '2333', NULL, '2017-06-01 16:29:36', '2017-06-01 16:29:36', 15),
(4, 42, 40, '23331', NULL, '2017-06-01 16:30:42', '2017-06-01 16:30:42', 15),
(5, 42, 51, '我来写个笔记', 1, '2017-06-01 16:31:02', '2017-06-01 16:31:02', 13),
(6, 42, 40, '11111', 1, '2017-06-02 17:18:49', '2017-06-02 17:18:49', 15);

-- --------------------------------------------------------

--
-- 表的结构 `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `position`
--

INSERT INTO `position` (`id`, `name`, `created_time`) VALUES
(1, '校长', '2017-04-22 13:29:02'),
(4, '副校长', '2017-04-22 17:26:20'),
(5, '年级主任', '2017-04-22 17:26:30');

-- --------------------------------------------------------

--
-- 表的结构 `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `question_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `question_answer` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `answer` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `question_level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `question_type` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `questions`
--

INSERT INTO `questions` (`id`, `course_id`, `question_title`, `question_answer`, `answer`, `created_time`, `updated_time`, `question_level`, `question_type`) VALUES
(6, 15, '<p>根据阴阳的可分性，前半夜为</p>\n', 'a:4:{i:0;s:20:"<p>阴中之阳</p>\n";i:1;s:20:"<p>阴中之阴</p>\n";i:2;s:20:"<p>阳中之阴</p>\n";i:3;s:20:"<p>阳中之阳</p>\n";}', 's:1:"B";', '2017-05-22 22:21:32', '2017-05-22 22:21:32', '2', 0),
(8, 13, '<p>ds</p>\n', 'a:4:{i:0;s:10:"<p>ad</p>\n";i:1;s:10:"<p>ds</p>\n";i:2;s:9:"<p>d</p>\n";i:3;s:9:"<p>d</p>\n";}', 's:1:"A";', '2017-05-23 21:36:19', '2017-05-23 21:36:19', '0', 0),
(9, 15, '<p>&ldquo;阳病治阴&rdquo;的治疗原则适用于</p>\n', 'a:5:{i:0;s:20:"<p>阴虚阳亢</p>\n";i:1;s:20:"<p>阳虚阴胜</p>\n";i:2;s:20:"<p>阳气暴脱</p>\n";i:3;s:20:"<p>阴损及阳</p>\n";i:4;s:20:"<p>阳损及阴</p>\n";}', 's:1:"E";', '2017-05-23 21:47:06', '2017-05-23 21:47:06', '0', 0),
(10, 15, '<p>按五行生克规律肝之&ldquo;母&rdquo;是</p>\n', 'a:5:{i:0;s:11:"<p>心</p>\n";i:1;s:11:"<p>肾</p>\n";i:2;s:11:"<p>肺</p>\n";i:3;s:14:"<p>三焦</p>\n";i:4;s:11:"<p>脾</p>\n";}', 's:1:"B";', '2017-05-23 21:49:17', '2017-05-23 21:49:17', '1', 0),
(12, 15, '<p>中医学是发源于哪个国家的传统医学</p>\n', 'a:5:{i:0;s:14:"<p>中国</p>\n";i:1;s:14:"<p>日本</p>\n";i:2;s:14:"<p>印度</p>\n";i:3;s:14:"<p>埃及</p>\n";i:4;s:17:"<p>古希腊</p>\n";}', 'a:3:{i:0;s:1:"A";i:1;s:1:"C";i:2;s:1:"D";}', '2017-05-24 12:37:52', '2017-05-24 12:37:52', '0', 1);

-- --------------------------------------------------------

--
-- 表的结构 `recode`
--

CREATE TABLE `recode` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `recode`
--

INSERT INTO `recode` (`id`, `student_id`, `lesson_id`, `course_id`, `created_time`, `updated_time`) VALUES
(5, 292, 42, 15, '2017-06-02 21:51:07', '2017-06-02 21:51:07'),
(12, 292, 51, 13, '2017-06-02 22:33:28', '2017-06-02 22:33:28');

-- --------------------------------------------------------

--
-- 表的结构 `score`
--

CREATE TABLE `score` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `score`
--

INSERT INTO `score` (`id`, `student_id`, `exam_id`, `created_time`, `updated_time`, `score`) VALUES
(3, 292, 1, '2017-06-18 22:08:29', '2017-06-18 22:08:29', 33);

-- --------------------------------------------------------

--
-- 表的结构 `stgroup`
--

CREATE TABLE `stgroup` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `stgroup`
--

INSERT INTO `stgroup` (`id`, `name`, `created_time`, `updated_time`, `course_id`) VALUES
(1, '医学小组2017', '2017-05-08 09:03:59', '2017-05-08 09:03:59', 13),
(3, '医学小组2015', '2017-05-11 19:20:52', '2017-05-11 19:20:52', 15),
(4, '医学小组2016', '2017-05-26 18:36:44', '2017-05-26 18:36:44', 16),
(5, '针灸2017', '2017-06-07 22:18:42', '2017-06-07 22:18:42', 17),
(6, '针灸2016', '2017-06-07 22:19:01', '2017-06-07 22:19:01', 18);

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gendar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `tel` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `face` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`id`, `number`, `name`, `gendar`, `grade`, `created_time`, `updated_time`, `tel`, `face`, `info`) VALUES
(292, '5208150202', '陈佳珺', '女', '中医药学', '2017-04-29 17:01:21', '2017-04-29 17:01:21', '15105755111', NULL, NULL),
(293, '5208150203', '陈舒萍', '女', '中医药学', '2017-04-29 17:01:21', '2017-04-29 17:01:21', '15105755177', NULL, NULL),
(294, '5208150204', '陈星杰', '男', '中医药学', '2017-04-29 17:01:21', '2017-04-29 17:01:22', '15105755178', NULL, NULL),
(295, '5208150205', '陈震', '男', '中医药学', '2017-04-29 17:01:21', '2017-04-29 17:01:23', '15105755179', NULL, NULL),
(296, '5208150206', '池钰杰', '男', '针灸', '2017-04-29 17:01:21', '2017-04-29 17:01:23', '15105755180', NULL, NULL),
(297, '5208150207', '董傲婷', '女', '中医推拿', '2017-04-29 17:01:21', '2017-04-29 17:01:24', '', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `student_has_assignments`
--

CREATE TABLE `student_has_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `is_readed` tinyint(1) DEFAULT '0',
  `is_finished` tinyint(1) DEFAULT '0',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `student_has_assignments`
--

INSERT INTO `student_has_assignments` (`id`, `student_id`, `assignment_id`, `is_readed`, `is_finished`, `created_time`, `updated_time`) VALUES
(8, 292, 1, 1, 0, '2017-06-30 20:52:24', '2017-06-30 20:52:24'),
(9, 293, 1, 0, 0, '2017-06-30 20:52:24', '2017-06-30 20:52:24'),
(10, 294, 1, 0, NULL, '2017-06-30 20:52:24', '2017-06-30 20:52:24');

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gendar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `face` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_top` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`id`, `number`, `name`, `gendar`, `tel`, `roles`, `created_time`, `updated_time`, `position`, `face`, `info`, `is_top`) VALUES
(2, '4136', '陈小命', '女', '15105755177', '["ROLE_TEACHER"]', '2017-04-30 13:04:59', '2017-04-30 13:05:00', '副教授', 'b47fb574416dba4322a202479ce4522a.png', '优秀教师', 1),
(3, '4137', '陈星杰', '男', '15105755178', '["ROLE_TEACHER"]', '2017-04-30 13:04:59', '2017-04-30 13:05:00', '副教授', '82e0ae6fd14a2b25f4a5cf8318e9b09b.jpeg', '优秀教师', 1),
(4, '4138', '戴成接', '男', '15105755179', '["ROLE_TEACHER"]', '2017-04-30 13:04:59', '2017-04-30 13:05:01', NULL, NULL, NULL, 0),
(5, '4139', '王淑芳', '男', '15105755180', '["ROLE_TEACHER"]', '2017-04-30 13:04:59', '2017-04-30 13:05:02', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `teachers_stgroups`
--

CREATE TABLE `teachers_stgroups` (
  `teacher_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `teachers_stgroups`
--

INSERT INTO `teachers_stgroups` (`teacher_id`, `group_id`) VALUES
(2, 1),
(3, 1),
(3, 3);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `created_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `roles`, `created_time`) VALUES
(6, 'admin', '$2y$13$gHNMeLyhm1Q4hNDKq.fx1Oj71zEvTKVLP9390ROarh7RkIkOKKoIW', '["ROLE_ADMIN"]', '0000-00-00 00:00:00'),
(39, 'student', '$2y$13$jlx4cXt.X81qfOyhHRIJguNveimxRAETF4LBWeHoBjUy.2pscxwla', '["ROLE_STUDENT"]', '2017-04-22 22:14:23'),
(42, '5208150202', '$2y$13$ySgMyoLfz.a9pBmkI0X4m.EEBN18J8dowlAvNMD5OROxzADyOjIMO', '["ROLE_STUDENT"]', '2017-04-29 17:01:21'),
(43, '5208150203', '$2y$13$C0bGPpy0l8O6YWblV66JIu7bQdGAbOhOVqMK9MVZT9TJm1tet1zrC', '["ROLE_STUDENT"]', '2017-04-29 17:01:21'),
(44, '5208150204', '$2y$13$OUfnVoyFQZrvCWI.N0Bs..ErsopwzHxAE8CvncWo4xGpszBlcwxeS', '["ROLE_STUDENT"]', '2017-04-29 17:01:22'),
(45, '5208150205', '$2y$13$jeg6DVKjx36MyM8.dDktmuPDnLdw4EGkkKUlGOEhEq/RmBdg.eou6', '["ROLE_STUDENT"]', '2017-04-29 17:01:23'),
(46, '5208150206', '$2y$13$00Jgw6Y0m1wLXc1M18lrpOP4wHJjf1NCA96gmyJO34sgYaAAycWIi', '["ROLE_STUDENT"]', '2017-04-29 17:01:23'),
(47, '5208150207', '$2y$13$zP33Ab97aA87WGhQGwEWV.ZlVmDm1FLNlzswggaI0B/FCHANiRjfG', '["ROLE_STUDENT"]', '2017-04-29 17:01:24'),
(55, '4136', '$2y$13$Tc66YLsKIGdpbwpgh2sryuLhdqGzI8wfYX8NVoofWYzSTi2A4h9sy', '["ROLE_TEACHER"]', '2017-04-30 13:05:00'),
(56, '4137', '$2y$13$oU236iwMdDO2LbYVarOrreoVtD/GRAsGbDtpZ3JjrG6ZWe7Aa0uxC', '["ROLE_TEACHER"]', '2017-04-30 13:05:00'),
(57, '4138', '$2y$13$h6On8GiONeJIRVdZVqXtHOFtABq7LPLyA.U0N6TTEZtXlYqNDU22O', '["ROLE_TEACHER"]', '2017-04-30 13:05:01'),
(58, '4139', '$2y$13$cYTL4ukqlfcznRGpaUGT/uJHWJ6rJwWSVgYau5SktriW/CiiTOwV6', '["ROLE_TEACHER"]', '2017-04-30 13:05:02'),
(64, '1221', '$2y$13$.UnW2PEtzrPPg.w3detdh.JzMnJwVNY3p5InTyys3n7O5yJnUNk2G', '["ROLE_ADVISOR"]', '2017-05-03 21:34:15'),
(65, '5555', '$2y$13$rYgxJeZsL2QgpeYvXWE66.iPwVO/n0Xrr/7zL8In3EYTVZReYhk/m', '["ROLE_ADVISOR"]', '2017-05-04 07:35:05'),
(66, '4444444', '$2y$13$AKF.dacY71J/GuesJUMNzOQ9kF3KfH80u46enE9WUIweG5HVLFkUq', '["ROLE_ADVISOR"]', '2017-05-04 09:49:54'),
(67, '11111111', '$2y$13$W55qkZwMHTYGcSFiC1sRmeu/sZ59V7cv.sfPGHkZa9dXJf6VPhqDq', '["ROLE_ADVISOR"]', '2017-06-09 11:38:54');

-- --------------------------------------------------------

--
-- 表的结构 `users_stgroups`
--

CREATE TABLE `users_stgroups` (
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `users_stgroups`
--

INSERT INTO `users_stgroups` (`student_id`, `group_id`) VALUES
(292, 1),
(292, 3),
(293, 1),
(293, 3),
(294, 1),
(294, 3),
(297, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisor`
--
ALTER TABLE `advisor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_19ADC9F496901F54` (`number`);

--
-- Indexes for table `advisors_stgroups`
--
ALTER TABLE `advisors_stgroups`
  ADD PRIMARY KEY (`advisor_id`,`group_id`),
  ADD KEY `IDX_E685906266D3AD77` (`advisor_id`),
  ADD KEY `IDX_E6859062FE54D947` (`group_id`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_30C544BA41807E1D` (`teacher_id`);

--
-- Indexes for table `assignment_replay`
--
ALTER TABLE `assignment_replay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E116D4EFA76ED395` (`user_id`),
  ADD KEY `IDX_E116D4EFD19302F8` (`assignment_id`);

--
-- Indexes for table `base_web`
--
ALTER TABLE `base_web`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C0155143CB944F1A` (`student_id`);

--
-- Indexes for table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F981B52E5E237E06` (`name`),
  ADD KEY `IDX_F981B52E591CC992` (`course_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9474526CFEC530A9` (`content`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`),
  ADD KEY `IDX_9474526CCDF80196` (`lesson_id`),
  ADD KEY `IDX_9474526CF8697D13` (`comment_id`),
  ADD KEY `IDX_9474526C591CC992` (`course_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_169E6FB95E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_169E6FB9FE54D947` (`group_id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_detail`
--
ALTER TABLE `evaluation_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E4794A64591CC992` (`course_id`),
  ADD KEY `IDX_E4794A64A76ED395` (`user_id`),
  ADD KEY `IDX_E4794A64456C5646` (`evaluation_id`),
  ADD KEY `IDX_E4794A64CB944F1A` (`student_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_38BBA6C6591CC992` (`course_id`);

--
-- Indexes for table `exam_question`
--
ALTER TABLE `exam_question`
  ADD PRIMARY KEY (`questions_id`,`exam_id`),
  ADD KEY `IDX_F593067DBCB134CE` (`questions_id`),
  ADD KEY `IDX_F593067D578D5E91` (`exam_id`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F87474F3579F4768` (`chapter_id`);

--
-- Indexes for table `lesson_question`
--
ALTER TABLE `lesson_question`
  ADD PRIMARY KEY (`questions_id`,`lesson_id`),
  ADD KEY `IDX_FEB00357BCB134CE` (`questions_id`),
  ADD KEY `IDX_FEB00357CDF80196` (`lesson_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D182A1182B36786B` (`title`);

--
-- Indexes for table `makers`
--
ALTER TABLE `makers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D7C4A1E11E27F6BF` (`question_id`),
  ADD KEY `IDX_D7C4A1E1CDF80196` (`lesson_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_11BA68CA76ED395` (`user_id`),
  ADD KEY `IDX_11BA68CCDF80196` (`lesson_id`),
  ADD KEY `IDX_11BA68C591CC992` (`course_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_462CE4F55E237E06` (`name`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8ADC54D5591CC992` (`course_id`);

--
-- Indexes for table `recode`
--
ALTER TABLE `recode`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recode_unique` (`student_id`,`lesson_id`),
  ADD KEY `IDX_F0AB1AD0CDF80196` (`lesson_id`),
  ADD KEY `IDX_F0AB1AD0591CC992` (`course_id`),
  ADD KEY `IDX_F0AB1AD0CB944F1A` (`student_id`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_32993751CB944F1A` (`student_id`),
  ADD KEY `IDX_32993751578D5E91` (`exam_id`);

--
-- Indexes for table `stgroup`
--
ALTER TABLE `stgroup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7BBDE9C75E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_7BBDE9C7591CC992` (`course_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B723AF3396901F54` (`number`);

--
-- Indexes for table `student_has_assignments`
--
ALTER TABLE `student_has_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5CE58FCCB944F1A` (`student_id`),
  ADD KEY `IDX_5CE58FCD19302F8` (`assignment_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B0F6A6D596901F54` (`number`);

--
-- Indexes for table `teachers_stgroups`
--
ALTER TABLE `teachers_stgroups`
  ADD PRIMARY KEY (`teacher_id`,`group_id`),
  ADD KEY `IDX_1FBFCFD441807E1D` (`teacher_id`),
  ADD KEY `IDX_1FBFCFD4FE54D947` (`group_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- Indexes for table `users_stgroups`
--
ALTER TABLE `users_stgroups`
  ADD PRIMARY KEY (`student_id`,`group_id`),
  ADD KEY `IDX_65340E2BCB944F1A` (`student_id`),
  ADD KEY `IDX_65340E2BFE54D947` (`group_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `advisor`
--
ALTER TABLE `advisor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `assignment`
--
ALTER TABLE `assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `assignment_replay`
--
ALTER TABLE `assignment_replay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `base_web`
--
ALTER TABLE `base_web`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `evaluation_detail`
--
ALTER TABLE `evaluation_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- 使用表AUTO_INCREMENT `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `makers`
--
ALTER TABLE `makers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `recode`
--
ALTER TABLE `recode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `score`
--
ALTER TABLE `score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `stgroup`
--
ALTER TABLE `stgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;
--
-- 使用表AUTO_INCREMENT `student_has_assignments`
--
ALTER TABLE `student_has_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- 限制导出的表
--

--
-- 限制表 `advisors_stgroups`
--
ALTER TABLE `advisors_stgroups`
  ADD CONSTRAINT `FK_E685906266D3AD77` FOREIGN KEY (`advisor_id`) REFERENCES `advisor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E6859062FE54D947` FOREIGN KEY (`group_id`) REFERENCES `stgroup` (`id`) ON DELETE CASCADE;

--
-- 限制表 `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `FK_30C544BA41807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`);

--
-- 限制表 `assignment_replay`
--
ALTER TABLE `assignment_replay`
  ADD CONSTRAINT `FK_E116D4EFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_E116D4EFD19302F8` FOREIGN KEY (`assignment_id`) REFERENCES `student_has_assignments` (`id`);

--
-- 限制表 `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `FK_C0155143CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- 限制表 `chapter`
--
ALTER TABLE `chapter`
  ADD CONSTRAINT `FK_F981B52E591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- 限制表 `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`),
  ADD CONSTRAINT `FK_9474526CF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- 限制表 `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `FK_169E6FB9FE54D947` FOREIGN KEY (`group_id`) REFERENCES `stgroup` (`id`);

--
-- 限制表 `evaluation_detail`
--
ALTER TABLE `evaluation_detail`
  ADD CONSTRAINT `FK_E4794A64456C5646` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`),
  ADD CONSTRAINT `FK_E4794A64591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_E4794A64A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_E4794A64CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- 限制表 `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `FK_38BBA6C6591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- 限制表 `exam_question`
--
ALTER TABLE `exam_question`
  ADD CONSTRAINT `FK_F593067D578D5E91` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F593067DBCB134CE` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- 限制表 `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `FK_F87474F3579F4768` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`);

--
-- 限制表 `lesson_question`
--
ALTER TABLE `lesson_question`
  ADD CONSTRAINT `FK_FEB00357BCB134CE` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FEB00357CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON DELETE CASCADE;

--
-- 限制表 `makers`
--
ALTER TABLE `makers`
  ADD CONSTRAINT `FK_D7C4A1E11E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `FK_D7C4A1E1CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);

--
-- 限制表 `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `FK_11BA68C591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_11BA68CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_11BA68CCDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);

--
-- 限制表 `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `FK_8ADC54D5591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- 限制表 `recode`
--
ALTER TABLE `recode`
  ADD CONSTRAINT `FK_F0AB1AD0591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `FK_F0AB1AD0CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `FK_F0AB1AD0CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);

--
-- 限制表 `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `FK_32993751578D5E91` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`),
  ADD CONSTRAINT `FK_32993751CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- 限制表 `stgroup`
--
ALTER TABLE `stgroup`
  ADD CONSTRAINT `FK_7BBDE9C7591CC992` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- 限制表 `student_has_assignments`
--
ALTER TABLE `student_has_assignments`
  ADD CONSTRAINT `FK_5CE58FCCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `FK_5CE58FCD19302F8` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`);

--
-- 限制表 `teachers_stgroups`
--
ALTER TABLE `teachers_stgroups`
  ADD CONSTRAINT `FK_1FBFCFD441807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1FBFCFD4FE54D947` FOREIGN KEY (`group_id`) REFERENCES `stgroup` (`id`) ON DELETE CASCADE;

--
-- 限制表 `users_stgroups`
--
ALTER TABLE `users_stgroups`
  ADD CONSTRAINT `FK_65340E2BCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_65340E2BFE54D947` FOREIGN KEY (`group_id`) REFERENCES `stgroup` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
