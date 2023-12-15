use mythicalclient;

DROP TABLE IF EXISTS `ip_logs`;

CREATE TABLE `ip_logs` (
  `id` int(11) NOT NULL,
  `ipaddr` text NOT NULL,
  `usertoken` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `ip_logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ip_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;