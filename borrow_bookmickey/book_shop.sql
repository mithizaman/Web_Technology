

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `book` (
  `id` int(5) NOT NULL,
  `bookTitle` varchar(500) NOT NULL,
  `bookAuthor` varchar(5000) NOT NULL,
  `isbn` varchar(15) NOT NULL,
  `bookCategory` varchar(100) NOT NULL,
  `bookQuantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `book` (`id`, `bookTitle`, `bookAuthor`, `isbn`, `bookCategory`, `bookQuantity`) VALUES
(2, 'book2', 'AIUB', '1234567891234', 'Science', 1094);


ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `book`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

