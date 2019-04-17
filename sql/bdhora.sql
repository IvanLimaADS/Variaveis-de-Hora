SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `bdhora` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bdhora`;

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `departamento` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `departamento` (`id`, `departamento`) VALUES
(1, 'TI'),
(2, 'OUTROS');

CREATE TABLE `nivel` (
  `id` int(11) NOT NULL,
  `nivel` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `nivel` (`id`, `nivel`) VALUES
(1, 'SUPERVISOR'),
(2, 'SUPERVISIONADO');

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Sobrenome` varchar(30) NOT NULL,
  `user` varchar(15) NOT NULL,
  `senha` varchar(12) NOT NULL,
  `departamento` int(11) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT '0',
  `funcao` varchar(40) NOT NULL,
  `matricula` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `Nome`, `Sobrenome`, `user`, `senha`, `departamento`, `nivel`, `funcao`, `matricula`) VALUES
(1, 'Master', ' ', 'master', '102030', 2, 1, '', '');

CREATE TABLE `variavel` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `entrada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `chamado` varchar(15) NOT NULL,
  `minuto` float AS (Timestampdiff(MINUTE, inicio, fim)) VIRTUAL,
  `semanaini` int(11) DEFAULT NULL,
  `minutoini` int(11) AS (CASE WHEN time_format(inicio, '%Y-%m-%d')=time_format(fim, '%Y-%m-%d') THEN timestampdiff(minute, inicio,fim) ELSE timestampdiff(minute, inicio, concat(time_format(inicio,'%Y-%m-%d'),' 23:59:59')) END) VIRTUAL,
  `semanafim` int(11) DEFAULT NULL,
  `minutofim` int(11) AS (CASE WHEN time_format(inicio, '%Y-%m-%d')=time_format(fim, '%Y-%m-%d')  THEN 0 ELSE timestampdiff(minute, concat(time_format(fim,'%Y-%m-%d'),' 00:00:00'), fim)+1 END) VIRTUAL,
  `noturnoini` datetime NOT NULL,
  `minutonotini` int(11) AS (CASE WHEN Timestampdiff(MINUTE, noturnoini, inicio) >= 0 THEN 120 - Timestampdiff(MINUTE, noturnoini, inicio) ELSE (CASE WHEN fim > noturnoini THEN 120 ELSE 0 END) END) VIRTUAL,
  `noturnofim` datetime NOT NULL,
  `minutonotfim` int(11) AS (CASE WHEN Timestampdiff(MINUTE, fim, noturnofim) < 0 THEN 0 ELSE 300 - Timestampdiff(MINUTE, fim, noturnofim) END) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vw_var` (
`empresa` varchar(10)
,`tipo` varchar(8)
,`idtipo` varchar(15)
,`entrada` datetime
,`funcao` varchar(40)
,`nome` varchar(61)
,`matricula` varchar(15)
,`descricao` varchar(16)
,`valor` decimal(33,0)
);

DROP TABLE IF EXISTS `vw_var`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_var`  AS  select 'GA SERVICE' AS `empresa`,'PROVENTO' AS `tipo`,`a`.`tipo` AS `idtipo`,`a`.`entrada` AS `entrada`,`b`.`funcao` AS `funcao`,concat(`b`.`Nome`,' ',`b`.`Sobrenome`) AS `nome`,`b`.`matricula` AS `matricula`,(case when ((`a`.`semanaini` in (0,6)) and (`a`.`tipo` <> 1)) then 'HORA EXTRA 100%' when ((`a`.`semanaini` not in (0,6)) and (`a`.`tipo` <> 1)) then 'HORA EXTRA 50%' else 'AUXILIO EDUCAÇÃO' end) AS `descricao`,(case when ((`a`.`semanaini` in (0,6)) and (`a`.`tipo` <> 1)) then sum(`a`.`minutoini`) when ((`a`.`semanaini` not in (0,6)) and (`a`.`tipo` <> 1)) then sum(`a`.`minutoini`) else 0 end) AS `valor` from (`variavel` `a` join `user` `b` on((`a`.`userid` = `b`.`id`))) group by concat(`b`.`Nome`,' ',`b`.`Sobrenome`),`a`.`semanaini`,`a`.`tipo` union select 'GA SERVICE' AS `empresa`,'PROVENTO' AS `tipo`,`a`.`tipo` AS `idtipo`,`a`.`entrada` AS `entrada`,`b`.`funcao` AS `funcao`,concat(`b`.`Nome`,' ',`b`.`Sobrenome`) AS `nome`,`b`.`matricula` AS `matricula`,(case when ((`a`.`semanafim` in (0,6)) and (`a`.`tipo` <> 1)) then 'HORA EXTRA 100%' when ((`a`.`semanafim` not in (0,6)) and (`a`.`tipo` <> 1)) then 'HORA EXTRA 50%' else 'AUXILIO EDUCAÇÃO' end) AS `descricao`,(case when ((`a`.`semanafim` in (0,6)) and (`a`.`tipo` <> 1)) then sum(`a`.`minutofim`) when ((`a`.`semanafim` not in (0,6)) and (`a`.`tipo` <> 1)) then sum(`a`.`minutofim`) else 0 end) AS `valor` from (`variavel` `a` join `user` `b` on((`a`.`userid` = `b`.`id`))) group by concat(`b`.`Nome`,' ',`b`.`Sobrenome`),`a`.`semanafim` union select distinct 'GA SERVICE' AS `empresa`,'PROVENTO' AS `tipo`,`a`.`tipo` AS `idtipo`,`a`.`entrada` AS `entrada`,`b`.`funcao` AS `funcao`,concat(`b`.`Nome`,' ',`b`.`Sobrenome`) AS `nome`,`b`.`matricula` AS `matricula`,'AUXILIO EDUCAÇÃO' AS `descricao`,300 AS `valor` from (`variavel` `a` join `user` `b` on((`a`.`userid` = `b`.`id`))) where (`a`.`tipo` = 1) union select 'GA SERVICE' AS `empresa`,'PROVENTO' AS `tipo`,`a`.`tipo` AS `idtipo`,`a`.`entrada` AS `entrada`,`b`.`funcao` AS `funcao`,concat(`b`.`Nome`,' ',`b`.`Sobrenome`) AS `nome`,`b`.`matricula` AS `matricula`,'AD. NOTURNO' AS `descrcao`,(sum(`a`.`minutonotini`) + sum(`a`.`minutonotfim`)) AS `valor` from (`variavel` `a` join `user` `b` on((`a`.`userid` = `b`.`id`))) where (`a`.`tipo` = 2) group by concat(`b`.`Nome`,' ',`b`.`Sobrenome`),`a`.`tipo`,`a`.`entrada`,`b`.`funcao`,`b`.`matricula` ;

ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `nivel`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `variavel`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `nivel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `variavel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
