-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 16, 2025 lúc 07:45 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `nl`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ID_DH` int(11) NOT NULL,
  `ID_SP` int(11) NOT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `co_gia`
--

CREATE TABLE `co_gia` (
  `NgayGio` datetime NOT NULL,
  `ID_SP` int(11) NOT NULL,
  `Gia` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `co_gia`
--

INSERT INTO `co_gia` (`NgayGio`, `ID_SP`, `Gia`) VALUES
('2025-01-15 13:54:07', 1, 5490000),
('2025-01-15 13:54:07', 2, 8290000),
('2025-01-15 13:54:07', 3, 8990000),
('2025-01-15 13:54:07', 4, 6290000),
('2025-01-15 13:54:07', 5, 6490000),
('2025-01-15 13:54:07', 6, 5890000),
('2025-01-15 13:54:07', 7, 7890000),
('2025-01-15 13:54:07', 8, 1390000),
('2025-01-15 13:54:07', 9, 1090000),
('2025-01-15 13:54:07', 10, 2390000),
('2025-01-15 13:54:07', 11, 6399000),
('2025-01-15 13:54:07', 12, 9690000),
('2025-01-15 13:54:07', 13, 7699000),
('2025-01-15 13:54:07', 14, 5490000),
('2025-01-15 13:54:07', 15, 8990000),
('2025-01-15 13:54:07', 16, 13490000),
('2025-01-15 13:54:07', 17, 11490000),
('2025-01-15 13:54:07', 18, 13578000),
('2025-01-15 13:54:07', 19, 11567000),
('2025-01-15 13:54:07', 20, 3456000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ID_KH` int(11) NOT NULL,
  `ID_SP` int(11) NOT NULL,
  `ID_DH` int(11) NOT NULL,
  `NoiDung` text DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Created_at` datetime DEFAULT NULL,
  `Updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `ID_DC` int(11) NOT NULL,
  `ID_XP` int(11) DEFAULT NULL,
  `ID_DH` int(11) DEFAULT NULL,
  `SoNha` varchar(50) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `ChiPhiGiao` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `ID_DH` int(11) NOT NULL,
  `ID_NV` int(11) DEFAULT NULL,
  `ID_KH` int(11) DEFAULT NULL,
  `ID_TT` int(11) DEFAULT NULL,
  `TrangThai_DH` varchar(30) DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL,
  `TongTien` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `huyen_quan`
--

CREATE TABLE `huyen_quan` (
  `ID_HQ` int(11) NOT NULL,
  `ID_T` int(11) DEFAULT NULL,
  `Ten_HQ` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `ID_KH` int(11) NOT NULL,
  `Ho_KH` varchar(10) DEFAULT NULL,
  `Ten_KH` varchar(15) DEFAULT NULL,
  `TrangThai_KH` varchar(30) DEFAULT NULL,
  `Email_KH` varchar(50) DEFAULT NULL,
  `SDT_KH` char(10) DEFAULT NULL,
  `MatKhau_KH` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`ID_KH`, `Ho_KH`, `Ten_KH`, `TrangThai_KH`, `Email_KH`, `SDT_KH`, `MatKhau_KH`) VALUES
(1, 'Lê', 'Thị Hà', NULL, 'ha36@gmail.com', '0695834508', '1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_sp`
--

CREATE TABLE `loai_sp` (
  `ID_L` int(11) NOT NULL,
  `Ten_L` varchar(30) DEFAULT NULL,
  `HinhAnh_L` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_sp`
--

INSERT INTO `loai_sp` (`ID_L`, `Ten_L`, `HinhAnh_L`) VALUES
(1, 'Phòng Ăn', 'b.png'),
(2, 'Phòng Khách', 'a.png'),
(4, 'Phòng Ngủ', 'c.png'),
(5, 'Đồ Trang Trí', 'e.jpg'),
(6, 'Phòng Làm Việc', 'k.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `ID_NV` int(11) NOT NULL,
  `Ho_NV` varchar(10) DEFAULT NULL,
  `Ten_NV` varchar(15) DEFAULT NULL,
  `VaiTro` varchar(30) DEFAULT NULL,
  `TrangThai_NV` varchar(30) DEFAULT NULL,
  `Email_NV` varchar(50) DEFAULT NULL,
  `SDT_NV` char(10) DEFAULT NULL,
  `DiaChi_NV` varchar(150) DEFAULT NULL,
  `MatKhau_NV` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`ID_NV`, `Ho_NV`, `Ten_NV`, `VaiTro`, `TrangThai_NV`, `Email_NV`, `SDT_NV`, `DiaChi_NV`, `MatKhau_NV`) VALUES
(1, 'Nguyễn', 'Văn An', 'Admin', NULL, 'an45@gmail.com', '0237686950', NULL, '123'),
(2, 'Nguyễn', 'Văn Bình', 'Admin', NULL, 'binh75@gmail.com', '0237686978', NULL, '123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuong_thuc_thanh_toan`
--

CREATE TABLE `phuong_thuc_thanh_toan` (
  `ID_TT` int(11) NOT NULL,
  `Ten_TT` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `ID_SP` int(11) NOT NULL,
  `ID_L` int(11) DEFAULT NULL,
  `ID_TH` int(11) DEFAULT NULL,
  `Ten_SP` varchar(30) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `SoLuongKho` int(11) DEFAULT NULL,
  `HinhAnh` varchar(100) DEFAULT NULL,
  `GiamGia` float DEFAULT NULL,
  `KichThuoc` text DEFAULT NULL,
  `ChatLieu` text DEFAULT NULL,
  `HanDung` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`ID_SP`, `ID_L`, `ID_TH`, `Ten_SP`, `MoTa`, `SoLuongKho`, `HinhAnh`, `GiamGia`, `KichThuoc`, `ChatLieu`, `HanDung`) VALUES
(1, 1, NULL, 'Bàn Ăn Gỗ Cao Su Tự Nhiên', 'Bộ sưu tập MONZA mang đậm phong cách thiết kế Korean Minimalist, kết hợp với sự thanh lịch, tối giản của Scandinavian Modern. Với tinh thần \"Less is More\", tủ MONZA là lựa chọn lý tưởng cho những ai tìm kiếm sự tinh tế, gọn gàng và tiện nghi trong không gian sống hiện đại.', 10, 'Banangocaosutunhien.jpg', NULL, NULL, 'Gỗ công nghiệp phủ Melamine CARB-P2', NULL),
(2, 1, NULL, 'Bộ Bàn Ăn 4 Ghế Gỗ Cao Su', 'Bàn Ăn Gỗ Cao Su MOHO OSLO 901 hiện đại và đẹp đẽ được thể hiện qua từng đường nét thiết kế tỉ mỉ, tối giản. Sản phẩm có thể bài trí hài hòa được nhiều không gian nội thất phòng ăn và kết hợp nhiều mẫu ghế ăn khác nhau để cho ra đời bộ bàn ăn gia đình thích hợp với nhu cầu của bạn.', 10, 'Bobanango4ghecaosu.jpg', NULL, '0', 'Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Veneer gỗ cao su tự nhiên', NULL),
(3, 1, NULL, 'Ghế Ăn Gỗ Cao Su Tự Nhiên', 'Bàn Ăn Gỗ Cao Su MOHO OSLO 901 hiện đại và đẹp đẽ được thể hiện qua từng đường nét thiết kế tỉ mỉ, tối giản. Sản phẩm có thể bài trí hài hòa được nhiều không gian nội thất phòng ăn và kết hợp nhiều mẫu ghế ăn khác nhau để cho ra đời bộ bàn ăn gia đình thích hợp với nhu cầu của bạn.', 10, 'Gheangocaosutunhien.jpg', NULL, NULL, 'Gỗ cao su trên mặt bàn giúp bề mặt nhẵn mịn và màu sắc đẹp tự nhiên đầy hiện đại.', NULL),
(4, 1, NULL, 'Bàn Ăn Gỗ Tràm Tự Nhiên', 'Bàn Ăn Gỗ Cao Su MOHO OSLO 901 hiện đại và đẹp đẽ được thể hiện qua từng đường nét thiết kế tỉ mỉ, tối giản. Sản phẩm có thể bài trí hài hòa được nhiều không gian nội thất phòng ăn và kết hợp nhiều mẫu ghế ăn khác nhau để cho ra đời bộ bàn ăn gia đình thích hợp với nhu cầu của bạn.', NULL, 'Banangotramtunhien.jpg', NULL, 'Dài 50cm x Rộng 51cm x Cao 81cm ', 'Sử dụng chất liệu gỗ cao su giúp sản phẩm có khả năng chịu lực tốt và độ bền cao.', NULL),
(5, 2, NULL, 'Ghế Sofa Gỗ Cao Su Tự Nhiên', 'Thiết kế của ghế sofa LYNGBY hiện đại nhưng đơn giản, bao gồm thêm khóa dán bên dưới sẽ giúp nệm ghế cố định khi ngồi, bạn sẽ không còn lo lắng về việc tấm nệm bị trượt ra bên ngoài mỗi khi sử dụng.', 10, 'GheSofagocaosutunhien.jpg', NULL, 'Dài 160cm x Rộng 79cm x Cao 72cm', 'Chất liệu gỗ cao su tự nhiên không chỉ mang lại sức chịu lực tốt mà còn đảm bảo độ bền cao cho ghế sofa LYNGBY. Có độ bền cao, đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức. ', NULL),
(6, 2, NULL, 'Ghế Đôn Sofa Gỗ Tràm Tự Nhiên', 'Bộ sưu tập Dalumd là bản giao hưởng hoàn hảo giữa phong cách tối giản (Minimalist) và phong cách thanh lịch hiện đại (Modern Elegant). Lấy cảm hứng từ tinh thần \"Less is More\", mỗi chi tiết trong thiết kế đều được chắt lọc tỉ mỉ, đơn giản nhưng không kém phần tinh tế, tôn vinh vẻ đẹp bền vững của chất liệu gỗ và sự hài hòa trong tổng thể.', 10, 'GhedonSofagotramtunhien.jpg', NULL, 'Dài 180cm x Rộng 85cm x Cao 78cm', 'Sofa Dalumd được làm từ khung gỗ cao su với kết cấu chắc chắn, chịu lực tốt và độ bền cao. Đặc biệt, nguyên liệu gỗ đã được xử lý chống mối mọt, giúp hạn chế khả năng bị cong vênh và biến dạng trong quá trình sử dụng', NULL),
(7, 2, NULL, 'Ghế Sofa Gốc Chữ L', 'Bộ sưu tập Dalumd là bản giao hưởng hoàn hảo giữa phong cách tối giản (Minimalist) và phong cách thanh lịch hiện đại (Modern Elegant). Lấy cảm hứng từ tinh thần \"Less is More\", mỗi chi tiết trong thiết kế đều được chắt lọc tỉ mỉ, đơn giản nhưng không kém phần tinh tế, tôn vinh vẻ đẹp bền vững của chất liệu gỗ và sự hài hòa trong tổng thể.', NULL, 'GheSofagocchuL.jpg', NULL, ' Dài 209 x Rộng 167/ 187 x Cao 90 cm', 'Sử dụng gỗ tràm tự nhiên đảm bảo vệ độ chắc chắn cao, chống công vênh, mối mọt cho giường ngủ. Veneer gỗ tràm giúp tăng giá trị và thẩm mỹ của giường ngủ VLINE, đem đến những vân gỗ đẹp tự nhiên cùng màu sắc thu hút.', NULL),
(8, 2, NULL, 'Tủ Kê Ti Vi Gỗ', 'Bàn trà sofa chữ nhật OSLO giúp mang lại không gian hiện đại cho không gian nội thất phòng khách với màu sắc tự nhiên và màu nâu sang trọng, chúng sẽ tô điểm cho không gian nhà bạn trở nên đầy tính thẩm mỹ.', 10, 'TukeTivigo.jpg', NULL, NULL, 'Mặt bàn màu tự nhiên: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Veneer gỗ sồi', NULL),
(9, 6, NULL, 'Bàn Làm Việc Gỗ', 'Bàn làm việc VLINE có thể phối hợp với nhiều phong cách nội thất từ nội thất phòng khách đến nội thất phòng ngủ.', 10, 'Banlamviecgo.jpg', NULL, 'Dài 110cm x Rộng 55cm x Cao 74cm', 'Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Veneer gỗ tràm tự nhiên', NULL),
(10, 6, NULL, 'Bàn Làm Việc Gỗ Có Kệ', 'Bộ sưu tập nội thất OSLO lấy ý tưởng từ những nhịp sống hiện đại, hối hả của thành phố OSLO của Na Uy. Đặc trưng của các sản phẩm từ bộ sưu tập nội thất này là những đường nét bo tròn đến hoàn hảo. Đặc biệt chi tiết hình tròn được tận dụng một cách độc đáo và mới mẻ vào trong từng thiết kế, thổi làn gió hiện đại cho mọi không gian. ', 10, 'Banlamviecgocoke.jpg', NULL, 'Dài 80cm x Rộng 30cm x Cao 160cm', 'MOHO sử dụng 100% chất liệu gỗ công nghiệp (PB, MDF) đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức khỏe người dùng và đạt chứng nhận FSC bảo vệ và phát triển rừng bền vững.', NULL),
(11, 6, NULL, 'Ghế Văn Phòng Chân Xoay', 'Với thiết kế tối ưu, tựa lưng sử dụng chất liệu nhựa PP cao cấp qua đó hình thành nên đường nét bo cong tinh tế, chắc chắn, là điểm tựa vững chắc cho phần thân trên cơ thể người dùng, hỗ trợ nâng đỡ tối ưu và bảo vệ cột sống người ngồi.', 10, 'Ghevanphongchanxoay.jpg', NULL, 'Dài 52cm x Rộng 65cm x Cao 94-101cm', 'Với chất liệu vải nhập cao cấp, thoáng khí giúp bạn luôn thoải mái dù sử dụng trong nhiều giờ', NULL),
(12, 6, NULL, 'Học Tủ 3 Ngăn Lưu Trữ Tài Liệu', 'Các thiết kế trong bộ sưu tập nội thất VLINE mang trong mình nét đẹp đặc trưng của hồn Việt cùng vẻ đẹp năng động của thời đại. V” là viết tắt của từ “Việt” trong “Việt Nam”. “LINE” là các đường nét mang xu hướng hiện đại, phóng khoáng của ngày nay. ', NULL, 'Hoctu3nganluutrutailieu.jpg', NULL, 'Dài 116cm x Rộng 30/51cm x Cao 74/136cm ', 'Sử dụng chất liệu gỗ công nghiệp (PB, MDF) đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức khỏe người dùng và đạt chứng nhận FSC bảo vệ và phát triển rừng, góp phần vào sự phát triển rừng một cách bền vững.', NULL),
(13, 4, NULL, 'Bàn Trang Điểm Gỗ Đa Năng', 'Bộ sưu tập Dalumd là bản giao hưởng hoàn hảo giữa phong cách tối giản (Minimalist) và phong cách thanh lịch hiện đại (Modern Elegant). Lấy cảm hứng từ tinh thần \"Less is More\", mỗi chi tiết trong thiết kế đều được chắt lọc tỉ mỉ, đơn giản nhưng không kém phần tinh tế, tôn vinh vẻ đẹp bền vững của chất liệu gỗ và sự hài hòa trong tổng thể.', 10, 'Bantrangdiemgodanang.jpg', NULL, 'Dài 40 x Rộng 55 x Cao 50 cm', 'Sử dụng chất liệu gỗ cao su giúp giường ngủ VLINE có khả năng chịu lực tốt và độ bền cao. ', NULL),
(14, 4, NULL, 'Giường Ngủ Bọc Nệm', 'Sự kết hợp giữa tủ quần áo MONZA và giường có hộc VIENNA STORAGE sẽ là giải pháp lưu trữ tối ưu và hoàn hảo nhất cho không gian phòng ngủ luôn gọn gàng ngăn nắp, đặc biệt phù hợp với những phòng ngủ có diện tích nhỏ.\r\n\r\n', NULL, 'Giuongngubocnem.jpg', NULL, 'Dài 212cm x Rộng 136/156/176/196cm', 'Sử dụng gỗ tràm tự nhiên đảm bảo vệ độ chắc chắn cao, chống công vênh, mối mọt cho giường ngủ của bạn.', NULL),
(15, 4, NULL, 'Giường Ngủ Gỗ Tràm', 'Sự kết hợp giữa tủ quần áo MONZA và giường có hộc VIENNA STORAGE sẽ là giải pháp lưu trữ tối ưu và hoàn hảo nhất cho không gian phòng ngủ luôn gọn gàng ngăn nắp, đặc biệt phù hợp với những phòng ngủ có diện tích nhỏ.', NULL, 'Giuongngugotram.jpg', NULL, NULL, 'Sản phẩm sử dụng chất liệu gỗ công nghiệp (PB, MDF) đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức khỏe người dùng và đạt chứng nhận FSC bảo vệ và phát triển rừng.', NULL),
(16, 4, NULL, 'Set Tủ Quần Áo Gỗ', 'VIENNA là niềm ao ước đặt chân đến của nhiều người bởi nó là thủ đô nổi tiếng bậc nhất về sự lộng lẫy của các tòa lâu đài nguy nga, tráng lệ. Đây là nguồn cảm hứng cho bộ sưu tập nội thất VIENNA, nhờ vậy mà các sản phẩm thuộc bộ sưu tập này đều mang nét hiện đại của thủ đô nước Áo.', 10, 'Settuquanaogo.jpg', NULL, NULL, 'Sản phẩm sử dụng chất liệu gỗ công nghiệp (PB, MDF) đạt chuẩn CARB-P2 an toàn tuyệt đối cho người sức khỏe người dùng và đạt chứng nhận FSC bảo vệ và phát triển rừng.', NULL),
(17, 5, NULL, 'Bộ 3 Tranh', 'Tủ đầu giường FIJI được thiết kế tối giản kết hợp với vẻ đẹp của chất liệu mây đan tự nhiên chắc chắn sẽ thổi thêm nét xanh và mang đến cho không gian nội thất phòng ngủ của bạn vẻ đẹp Á Đông mới. Bạn có thể cảm nhận sự tươi mới như đang thong dong giữa một cánh rừng xanh mát bất tận.', NULL, 'Bo3tranh.jpg', NULL, ' Dài 50cm x Rộng 45cm x Cao 50.5cm', 'Sử dụng chất liệu gỗ cao su giúp tủ đầu giường ngủ FIJI có khả năng chịu lực tốt và độ bền cao, đảm bảo sự vững chắc và chống cong vênh, mối mọt trong suốt thời gian sử dụng.', NULL),
(18, 5, NULL, 'Đèn Trần Tua Rua', 'Nét đặc trưng của thiết kế HOBRO là những đường veneer xếp đan chéo chỉnh chu từng chi tiết. Với sự khéo léo, tỉ mỉ được làm thủ công từng giai đoạn từ khâu chuẩn bị đến hoàn tất để tạo ra những đường veneer chuẩn đối xứng.', 10, 'Dentrantuarua.jpg', NULL, 'Dài 60cm x Rộng 40cm x Cao 50cm', 'Sử dụng gỗ tràm tự nhiên đảm bảo vệ độ chắc chắn cao, chống công vênh, mối mọt cho tu đầu giường nhà bạn.', NULL),
(19, 5, NULL, 'Đồng Hồ Treo Tường', 'Bộ sưu tập nội thất VIENNA là bộ sưu tập nội thất tâm huyết đến từ nội thất MOHO, xu hướng chủ đạo của các thiết kế này là đem hơi thở hiện đại trong từng sản phẩm. Khi sử dụng các sản phẩm nội thất khác như tủ quần áo, tủ giày trong bộ sưu tập VIENNA Collection - khách hàng của nội thất MOHO sẽ cảm nhận được nhịp sống hiện đại và sang trọng như được sống trong thủ đô VIENNA nước Áo.', 10, 'Donghotreotuong.jpg', NULL, 'Dài 100cm x Rộng 40cm x Cao 75cm', 'Nhờ chất liệu gỗ MFC cao cấp, bàn trang điểm Vienna không chỉ cứng cáp mà còn chắc chắn, chịu lực tốt và không bị cong vênh theo thời gian.', NULL),
(20, 5, NULL, 'Kệ Gỗ 2 Tầng Treo Tường', 'Khách hàng của Nội Thất MOHO có thể mua kèm Gương trang điểm Vienna để kết hợp thành bộ sản phẩm hoàn chỉnh. Cả 2 sản phẩm sẽ được đội ngũ nội thất MOHO gắn chắc chắn lại với nhau khi giao hàng. Gương đi kèm có kích thước 55cm x 60cm, được chế tác bằng chất liệu cao cấp, an toàn, khi vỡ sẽ hạn chế được sát thương cho người dùng. Ngoài ra, gương trang điểm còn hạn chế bám bụi và dễ lau chùi.', 10, 'Kego2tangtreotuong.jpg', NULL, 'Dài 100cm x Rộng 40cm x Cao 75cm', 'Gỗ công nghiệp MFC, gương cao cấp dễ lau chùi, hạn chế bám bụi và hạn chế được sát thương nếu bị vỡ', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thoi_diem`
--

CREATE TABLE `thoi_diem` (
  `NgayGio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `thoi_diem`
--

INSERT INTO `thoi_diem` (`NgayGio`) VALUES
('2025-01-15 13:54:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `ID_TH` int(11) NOT NULL,
  `Ten_TH` varchar(30) DEFAULT NULL,
  `XuatXu` varchar(100) DEFAULT NULL,
  `MoTa_TH` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tinh_thanhpho`
--

CREATE TABLE `tinh_thanhpho` (
  `ID_T` int(11) NOT NULL,
  `Ten_T` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xa_phuong`
--

CREATE TABLE `xa_phuong` (
  `ID_XP` int(11) NOT NULL,
  `ID_HQ` int(11) DEFAULT NULL,
  `Ten_XP` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ID_DH`,`ID_SP`),
  ADD KEY `FK_CHI_TIET_RELATIONS_SAN_PHAM` (`ID_SP`);

--
-- Chỉ mục cho bảng `co_gia`
--
ALTER TABLE `co_gia`
  ADD PRIMARY KEY (`NgayGio`,`ID_SP`),
  ADD KEY `FK_CO_GIA_RELATIONS_SAN_PHAM` (`ID_SP`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ID_KH`,`ID_SP`,`ID_DH`),
  ADD KEY `FK_DANH_GIA_RELATIONS_DON_HANG` (`ID_DH`),
  ADD KEY `FK_DANH_GIA_RELATIONS_SAN_PHAM` (`ID_SP`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`ID_DC`),
  ADD KEY `FK_DIA_CHI__RELATIONS_DON_HANG` (`ID_DH`),
  ADD KEY `FK_DIA_CHI__RELATIONS_XA_PHUON` (`ID_XP`),
  ADD KEY `ID_DC` (`ID_DC`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ID_DH`),
  ADD KEY `FK_DON_HANG_RELATIONS_NHAN_VIE` (`ID_NV`),
  ADD KEY `FK_DON_HANG_RELATIONS_KHACH_HA` (`ID_KH`),
  ADD KEY `FK_DON_HANG_RELATIONS_PHUONG_T` (`ID_TT`);

--
-- Chỉ mục cho bảng `huyen_quan`
--
ALTER TABLE `huyen_quan`
  ADD PRIMARY KEY (`ID_HQ`),
  ADD KEY `FK_HUYEN_QU_RELATIONS_TINH_THA` (`ID_T`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`ID_KH`);

--
-- Chỉ mục cho bảng `loai_sp`
--
ALTER TABLE `loai_sp`
  ADD PRIMARY KEY (`ID_L`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`ID_NV`);

--
-- Chỉ mục cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  ADD PRIMARY KEY (`ID_TT`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ID_SP`),
  ADD KEY `FK_SAN_PHAM_RELATIONS_THUONG_H` (`ID_TH`),
  ADD KEY `FK_SAN_PHAM_RELATIONS_LOAI_SP` (`ID_L`);

--
-- Chỉ mục cho bảng `thoi_diem`
--
ALTER TABLE `thoi_diem`
  ADD PRIMARY KEY (`NgayGio`);

--
-- Chỉ mục cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`ID_TH`);

--
-- Chỉ mục cho bảng `tinh_thanhpho`
--
ALTER TABLE `tinh_thanhpho`
  ADD PRIMARY KEY (`ID_T`);

--
-- Chỉ mục cho bảng `xa_phuong`
--
ALTER TABLE `xa_phuong`
  ADD PRIMARY KEY (`ID_XP`),
  ADD KEY `FK_XA_PHUON_RELATIONS_HUYEN_QU` (`ID_HQ`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `ID_DC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ID_DH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `huyen_quan`
--
ALTER TABLE `huyen_quan`
  MODIFY `ID_HQ` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `ID_KH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `loai_sp`
--
ALTER TABLE `loai_sp`
  MODIFY `ID_L` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `ID_NV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  MODIFY `ID_TT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `ID_SP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `ID_TH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tinh_thanhpho`
--
ALTER TABLE `tinh_thanhpho`
  MODIFY `ID_T` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `xa_phuong`
--
ALTER TABLE `xa_phuong`
  MODIFY `ID_XP` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `FK_CHI_TIET_RELATIONS_DON_HANG` FOREIGN KEY (`ID_DH`) REFERENCES `don_hang` (`ID_DH`),
  ADD CONSTRAINT `FK_CHI_TIET_RELATIONS_SAN_PHAM` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`ID_SP`);

--
-- Các ràng buộc cho bảng `co_gia`
--
ALTER TABLE `co_gia`
  ADD CONSTRAINT `FK_CO_GIA_RELATIONS_SAN_PHAM` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`ID_SP`),
  ADD CONSTRAINT `FK_CO_GIA_RELATIONS_THOI_DIE` FOREIGN KEY (`NgayGio`) REFERENCES `thoi_diem` (`NgayGio`);

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `FK_DANH_GIA_RELATIONS_DON_HANG` FOREIGN KEY (`ID_DH`) REFERENCES `don_hang` (`ID_DH`),
  ADD CONSTRAINT `FK_DANH_GIA_RELATIONS_KHACH_HA` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`ID_KH`),
  ADD CONSTRAINT `FK_DANH_GIA_RELATIONS_SAN_PHAM` FOREIGN KEY (`ID_SP`) REFERENCES `san_pham` (`ID_SP`);

--
-- Các ràng buộc cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `FK_DIA_CHI__RELATIONS_DON_HANG` FOREIGN KEY (`ID_DH`) REFERENCES `don_hang` (`ID_DH`),
  ADD CONSTRAINT `FK_DIA_CHI__RELATIONS_XA_PHUON` FOREIGN KEY (`ID_XP`) REFERENCES `xa_phuong` (`ID_XP`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `FK_DON_HANG_RELATIONS_KHACH_HA` FOREIGN KEY (`ID_KH`) REFERENCES `khach_hang` (`ID_KH`),
  ADD CONSTRAINT `FK_DON_HANG_RELATIONS_NHAN_VIE` FOREIGN KEY (`ID_NV`) REFERENCES `nhan_vien` (`ID_NV`),
  ADD CONSTRAINT `FK_DON_HANG_RELATIONS_PHUONG_T` FOREIGN KEY (`ID_TT`) REFERENCES `phuong_thuc_thanh_toan` (`ID_TT`);

--
-- Các ràng buộc cho bảng `huyen_quan`
--
ALTER TABLE `huyen_quan`
  ADD CONSTRAINT `FK_HUYEN_QU_RELATIONS_TINH_THA` FOREIGN KEY (`ID_T`) REFERENCES `tinh_thanhpho` (`ID_T`);

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `FK_SAN_PHAM_RELATIONS_LOAI_SP` FOREIGN KEY (`ID_L`) REFERENCES `loai_sp` (`ID_L`),
  ADD CONSTRAINT `FK_SAN_PHAM_RELATIONS_THUONG_H` FOREIGN KEY (`ID_TH`) REFERENCES `thuong_hieu` (`ID_TH`);

--
-- Các ràng buộc cho bảng `xa_phuong`
--
ALTER TABLE `xa_phuong`
  ADD CONSTRAINT `FK_XA_PHUON_RELATIONS_HUYEN_QU` FOREIGN KEY (`ID_HQ`) REFERENCES `huyen_quan` (`ID_HQ`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
