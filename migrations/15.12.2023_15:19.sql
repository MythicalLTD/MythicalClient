use mythicalclient;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `user_id` text NOT NULL,
  `token` text NOT NULL,
  `role` enum('Administrator','Admin','Support','Default') NOT NULL DEFAULT 'Default',
  `banned` text DEFAULT NULL,
  `first_ip` text NOT NULL,
  `last_ip` text NOT NULL,
  `coins` text NOT NULL DEFAULT '0',
  `2fa_code` text DEFAULT NULL,
  `verification_code` text DEFAULT NULL,
  `registred` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;