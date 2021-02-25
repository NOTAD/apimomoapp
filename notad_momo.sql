-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th2 25, 2021 lúc 09:07 AM
-- Phiên bản máy phục vụ: 10.3.27-MariaDB-log-cll-lve
-- Phiên bản PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `notad_momo`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `api_momo`
--

CREATE TABLE `api_momo` (
  `id` int(11) NOT NULL,
  `io` varchar(2) CHARACTER SET utf8 NOT NULL,
  `tranId` varchar(11) COLLATE utf8_bin NOT NULL,
  `partnerId` varchar(11) COLLATE utf8_bin NOT NULL,
  `partnerName` text COLLATE utf8_bin NOT NULL,
  `amount` varchar(10) CHARACTER SET utf8 NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `time` text COLLATE utf8_bin NOT NULL,
  `congtien` varchar(32) COLLATE utf8_bin NOT NULL,
  `sapco` int(11) NOT NULL,
  `sapco1` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Đang đổ dữ liệu cho bảng `api_momo`
--

INSERT INTO `api_momo` (`id`, `io`, `tranId`, `partnerId`, `partnerName`, `amount`, `comment`, `time`, `congtien`, `sapco`, `sapco1`) VALUES
(1, '1', '10117112471', '01687654818', 'LE HONG SON', '1000', 'Abc', '1614005816.058', '-1', -1, -1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `info`
--

CREATE TABLE `info` (
  `Sharer` text CHARACTER SET utf8 NOT NULL,
  `Sdt` text CHARACTER SET utf8 NOT NULL,
  `Fb` text CHARACTER SET utf8 NOT NULL,
  `Text` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `info`
--

INSERT INTO `info` (`Sharer`, `Sdt`, `Fb`, `Text`) VALUES
('Lê Hồng Sơn', '0387654818', 'https://www.facebook.com/notad.leson/', 'Sell api vcb, tcb liên hệ zalo ');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `api_momo`
--
ALTER TABLE `api_momo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `api_momo`
--
ALTER TABLE `api_momo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
