use mythicalclient;

DROP TABLE IF EXISTS `resetpasswords`;

CREATE TABLE `resetpasswords` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `ownerkey` text NOT NULL,
  `resetkeycode` text NOT NULL,
  `ip_address` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `resetpasswords`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `resetpasswords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;