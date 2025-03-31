function chatbot(input) {
  let responses = {
      // Lời chào
      "chào|xin chào|hi|hello|hey|alo|hallo|hê lô": "Xin chào! Chúng tôi có thể giúp gì cho bạn?",
      "bạn khỏe không|bạn thế nào|bạn có ổn không|hôm nay thế nào|có gì mới không": "Cảm ơn bạn đã hỏi! Tôi luôn sẵn sàng để hỗ trợ bạn.",
      "ngày hôm nay thế nào|hôm nay có gì đặc biệt không|hôm nay có khuyến mãi gì không": "Hôm nay chúng tôi có chương trình giảm giá đặc biệt! Bạn có muốn tìm hiểu thêm không?",
      "mình cần giúp đỡ|tôi cần hỗ trợ|giúp tôi với": "Đừng lo, tôi sẵn sàng hỗ trợ bạn! Hãy cho tôi biết bạn cần gì.",
      "tôi muốn hỏi một số thông tin|bạn có thể tư vấn cho tôi không|hỗ trợ tôi nhé": "Tất nhiên rồi! Bạn cần hỏi về sản phẩm, dịch vụ hay chính sách cửa hàng?",
      "cửa hàng có bao nhiêu chi nhánh|có nhiều chi nhánh không|cửa hàng ở đâu": "Hiện tại, chúng tôi chỉ có 1 chi nhánh tại Cần Thơ.",
      "bạn là robot à|bạn có phải AI không|bạn có phải con người không": "Tôi là trợ lý ảo thông minh của cửa hàng nội thất, luôn sẵn sàng hỗ trợ bạn!",
      "bạn có làm việc 24/7 không|giờ nào bạn hoạt động|khi nào bạn trả lời": "Tôi hoạt động 24/7, bất cứ khi nào bạn cần hỗ trợ.",
      "bạn có thể giúp tôi mua hàng không|có thể tư vấn mua hàng giúp tôi không": "Tất nhiên! Bạn đang quan tâm đến loại sản phẩm nào?",
      "hẹn gặp lại|tạm biệt nhé|hôm nay thế đủ rồi|bye bye": "Tạm biệt! Chúc bạn một ngày tốt lành!",
      "chào buổi sáng|chào buổi chiều|chào buổi tối": "Chào bạn! Chúc bạn có một ngày tuyệt vời!",
      "có ai ở đây không|ai đang ở đây": "Tôi là trợ lý ảo, luôn sẵn sàng để giúp bạn.",
      "bạn có thể nói gì không|bạn có thể làm gì": "Tôi có thể giúp bạn tìm kiếm sản phẩm và trả lời câu hỏi về cửa hàng.",
      "bạn có vui không|bạn có hạnh phúc không": "Tôi không có cảm xúc, nhưng tôi rất vui khi được giúp bạn!",
      "bạn có thể giúp tôi không|giúp tôi với": "Tất nhiên! Bạn cần hỗ trợ gì?",
      "bạn có biết gì về cửa hàng không|cửa hàng này như thế nào": "Cửa hàng chúng tôi chuyên cung cấp đồ nội thất chất lượng cao.",
      "bạn có thể giới thiệu về cửa hàng không|cửa hàng của bạn có gì đặc biệt": "Chúng tôi cung cấp nhiều sản phẩm nội thất đa dạng và dịch vụ tư vấn chuyên nghiệp.",
      "bạn có thể nói về sản phẩm không|sản phẩm của bạn là gì": "Chúng tôi có nhiều loại sản phẩm như sofa, bàn ăn, giường ngủ và nhiều hơn nữa.",

      // Giới thiệu bản thân
      "bạn tên gì|bạn là ai|bạn là gì|có thể giới thiệu về bạn không": "Tôi là trợ lý ảo của cửa hàng nội thất. Bạn cần hỗ trợ gì không?",
      "bạn có thể làm gì|chức năng của bạn|bạn có thể giúp gì": "Tôi có thể giúp bạn tìm kiếm sản phẩm, tư vấn nội thất, hỗ trợ mua hàng và cung cấp thông tin về cửa hàng.",
      "bạn có thông minh không|bạn có AI không|bạn có học được không": "Tôi là một trợ lý AI thông minh, luôn học hỏi để phục vụ khách hàng tốt hơn!",
      "bạn có cảm xúc không|bạn có vui không|bạn có buồn không": "Tôi không có cảm xúc như con người, nhưng tôi luôn sẵn sàng giúp đỡ bạn!",
      "bạn có sợ ma không|bạn có thích phim kinh dị không": "Tôi không biết sợ là gì, nhưng nếu bạn thích trang trí nội thất theo phong cách bí ẩn, tôi có thể tư vấn cho bạn!",
      "bạn có biết chơi game không|bạn có biết về game không": "Tôi không chơi game, nhưng tôi có thể tư vấn nội thất cho phòng chơi game của bạn!",
      "bạn có thể đặt hàng giúp tôi không|có thể đặt hàng hộ tôi không": "Bạn có thể đặt hàng trực tiếp trên website, tôi sẽ hướng dẫn bạn nếu cần.",
      "bạn có thể nói chuyện với tôi không|tán gẫu với tôi đi|nói chuyện với tôi đi": "Tất nhiên! Tôi luôn sẵn sàng trò chuyện với bạn.",
      "bạn có thể giúp tôi học không|giúp tôi học bài đi": "Tôi có thể hỗ trợ bạn tìm kiếm thông tin liên quan đến nội thất và mua sắm!",
      "bạn có bạn bè không|có ai giống bạn không": "Tôi có rất nhiều trợ lý AI khác hỗ trợ khách hàng trên toàn thế giới!",
      "bạn có thể tư vấn không|tư vấn cho tôi đi": "Tôi có thể tư vấn cho bạn về các sản phẩm và cách chọn lựa phù hợp.",
      "bạn có thể tìm kiếm sản phẩm không|tìm sản phẩm cho tôi": "Tôi có thể giúp bạn tìm kiếm sản phẩm theo yêu cầu của bạn.",
      "bạn có thể giúp tôi đặt hàng không|đặt hàng cho tôi": "Tôi có thể hướng dẫn bạn cách đặt hàng trực tuyến.",
      "bạn có thể cung cấp thông tin không|thông tin về sản phẩm": "Tôi có thể cung cấp thông tin chi tiết về các sản phẩm của chúng tôi.",
      "bạn có thể giúp tôi so sánh sản phẩm không|so sánh sản phẩm cho tôi": "Tôi có thể giúp bạn so sánh các sản phẩm để bạn có lựa chọn tốt nhất.",
      "bạn có thể gửi thông tin qua email không|gửi thông tin cho tôi": "Tôi không thể gửi email, nhưng tôi có thể cung cấp thông tin ngay tại đây.",
      "bạn có thể giúp tôi tìm kiếm theo giá không|tìm sản phẩm theo giá": "Tôi có thể giúp bạn tìm kiếm sản phẩm theo mức giá bạn mong muốn.",
      "bạn có thể giúp tôi tìm sản phẩm theo màu không|tìm sản phẩm theo màu sắc": "Tôi có thể giúp bạn tìm sản phẩm theo màu sắc mà bạn thích.",

      // Sản phẩm và mua hàng
      "sản phẩm nổi bật|bán chạy|top sản phẩm|sản phẩm được yêu thích nhất": "Các sản phẩm nổi bật của chúng tôi gồm có sofa cao cấp, bàn ăn hiện đại và giường ngủ thông minh.",
      "có ghế gaming không|bán ghế chơi game không|bán bàn gaming không": "Chúng tôi có nhiều mẫu ghế và bàn gaming chất lượng cao, bạn muốn tìm loại nào?",
      "có kệ sách không|kệ sách có loại nào|bán kệ sách không": "Chúng tôi có kệ sách gỗ tự nhiên, kệ sắt hiện đại và kệ treo tường tiện lợi.",
      "bàn ăn có mấy loại|có bàn ăn mở rộng không|bán bàn ăn thông minh không": "Chúng tôi có bàn ăn gỗ, bàn ăn mặt kính, bàn ăn mở rộng và bàn ăn thông minh.",
      "tủ quần áo có sẵn không|có tủ quần áo gỗ không|bán tủ quần áo không": "Chúng tôi có tủ quần áo nhiều ngăn, cửa lùa, cửa mở, gỗ tự nhiên và công nghiệp.",
      "bán nệm không|nệm có loại nào|có nệm cao su không": "Chúng tôi có nệm cao su, nệm lò xo, nệm bông ép và nhiều thương hiệu uy tín.",
      "có bàn trang điểm không|bán bàn trang điểm không|bàn trang điểm loại nào đẹp": "Chúng tôi có bàn trang điểm gỗ, bàn trang điểm hiện đại và bàn có gương đèn LED.",
      "bán ghế sofa không|sofa có bao nhiêu loại|có sofa giường không": "Chúng tôi có sofa vải, sofa da, sofa giường và sofa góc.",
      "bán giường ngủ không|có giường tầng không|giường ngủ có bao nhiêu loại": "Chúng tôi có giường ngủ gỗ, giường sắt, giường tầng, giường hộp tiện lợi.",
      "bán bàn làm việc không|có bàn làm việc hiện đại không|bàn làm việc giá bao nhiêu": "Chúng tôi có bàn làm việc nhỏ gọn, bàn làm việc góc và bàn có kệ tiện lợi.",
      "có giao hàng không|ship hàng không|vận chuyển thế nào": "Chúng tôi hỗ trợ giao hàng tận nơi trên toàn quốc với nhiều phương thức vận chuyển linh hoạt.",
      "chính sách bảo hành|bảo hành thế nào": "Chúng tôi bảo hành sản phẩm từ 12 đến 36 tháng, tùy vào từng loại sản phẩm.",
      "giờ mở cửa|giờ làm việc": "Cửa hàng mở cửa từ 8:00 sáng đến 9:00 tối, tất cả các ngày trong tuần.",
      "có cửa hàng không|địa chỉ cửa hàng": "Cửa hàng chúng tôi tọa lạc tại phường Xuân Khánh, Ninh Kiều, Cần Thơ.",
      "có sản phẩm nào giảm giá không|khuyến mãi hiện tại": "Hiện tại chúng tôi đang có chương trình giảm giá lên đến 30% cho nhiều sản phẩm nội thất.",
      "có sản phẩm nội thất trẻ em không": "Chúng tôi có bộ sưu tập giường tầng, bàn học, ghế ngồi và tủ quần áo dành riêng cho trẻ em.",
      "chất liệu sofa có những loại nào": "Sofa có thể được làm từ da thật, da công nghiệp, vải nỉ hoặc vải bố tùy theo nhu cầu của bạn.",
      "có bán phụ kiện nội thất không": "Có! Chúng tôi cung cấp đèn trang trí, rèm cửa, thảm trải sàn, gối tựa, và nhiều phụ kiện khác.",
      "làm sao để đặt hàng": "Bạn có thể đặt hàng trực tiếp trên website hoặc liên hệ hotline để được hỗ trợ.",
      
      // Thanh toán và đổi trả
      "có thanh toán online không|thanh toán như thế nào|hỗ trợ thanh toán không": "Chúng tôi hỗ trợ thanh toán qua thẻ tín dụng, chuyển khoản ngân hàng và ví điện tử.",
      "có hỗ trợ trả góp không|mua hàng trả góp thế nào|trả góp có lãi không": "Chúng tôi có hỗ trợ trả góp 0% qua nhiều ngân hàng, bạn muốn tư vấn thêm không?",
      "chính sách đổi trả|có được đổi trả không|đổi trả hàng thế nào": "Bạn có thể đổi trả sản phẩm trong vòng 7 ngày nếu sản phẩm bị lỗi hoặc không đúng mô tả.",
      "có hoàn tiền không|hoàn tiền như thế nào|bị lỗi có hoàn tiền không": "Nếu sản phẩm bị lỗi do nhà sản xuất, chúng tôi hỗ trợ hoàn tiền hoặc đổi sản phẩm.",
      "giá sản phẩm như thế nào|có bảng giá không|sản phẩm giá bao nhiêu": "Giá sản phẩm được niêm yết trên website, bạn muốn xem giá sản phẩm nào?",
      "có miễn phí vận chuyển không|ship có mất phí không|giao hàng miễn phí không": "Chúng tôi miễn phí vận chuyển cho đơn hàng trên 5 triệu đồng tại Cần Thơ.",
      "có hỗ trợ lắp đặt không|có người lắp đặt giúp không|lắp đặt có mất phí không": "Chúng tôi hỗ trợ lắp đặt miễn phí với các sản phẩm nội thất lớn.",
      "giảm giá không|khuyến mãi gì không|có ưu đãi không": "Chúng tôi thường xuyên có chương trình giảm giá, bạn muốn tìm hiểu không?",
      "chính sách bảo hành|có bảo hành không|sản phẩm bảo hành bao lâu": "Chúng tôi bảo hành sản phẩm từ 12-36 tháng tùy vào loại sản phẩm.",
      "có thể đặt hàng trước không|hàng có sẵn không|bao lâu có hàng": "Một số sản phẩm cần đặt trước, thời gian giao hàng tùy vào địa điểm của bạn.",
      "thời gian giao hàng mất bao lâu|giao hàng trong bao lâu": "Thời gian giao hàng thường từ 3 đến 5 ngày làm việc, tùy vào địa điểm.",
      "có hỗ trợ trả góp không|mua hàng trả góp thế nào": "Chúng tôi có hỗ trợ trả góp với lãi suất 0% cho nhiều sản phẩm. Vui lòng liên hệ để biết thêm chi tiết.",
      "có hoàn tiền không|hoàn tiền thế nào": "Nếu sản phẩm bị lỗi do nhà sản xuất và bạn không muốn đổi sản phẩm khác, chúng tôi sẽ hoàn tiền cho bạn.",
      "thanh toán qua ví điện tử nào": "Chúng tôi hỗ trợ thanh toán qua Momo, ZaloPay và VNPay.",

      // Tư vấn nội thất
      "tư vấn nội thất|có hỗ trợ tư vấn không": "Chúng tôi có đội ngũ chuyên gia tư vấn giúp bạn chọn lựa sản phẩm phù hợp với không gian của bạn.",
      "cách chọn sofa|mua sofa như thế nào": "Hãy chọn sofa dựa vào diện tích phòng, chất liệu, màu sắc và phong cách thiết kế của bạn.",
      "bàn ăn bao nhiêu ghế|có bàn ăn 6 ghế không": "Chúng tôi có bàn ăn 4 ghế, 6 ghế và 8 ghế tùy theo nhu cầu của bạn.",
      "màu sắc nào phù hợp với phòng khách|phòng khách nên chọn màu gì": "Màu sắc phù hợp cho phòng khách thường là những màu trung tính như xám, be hoặc trắng.",
      "nên chọn chất liệu nào cho sofa|sofa nên làm bằng gì": "Sofa nên được làm bằng chất liệu bền, dễ vệ sinh như vải microfiber hoặc da tổng hợp.",
      "cách bố trí nội thất phòng khách đẹp": "Hãy chọn sofa, bàn trà và kệ tivi phù hợp với diện tích phòng. Nên sử dụng gam màu hài hòa để tạo không gian thoải mái.",
      "giường ngủ nên chọn kích thước bao nhiêu": "Chúng tôi có giường đơn (1m2x2m), giường đôi (1m6x2m) và giường king-size (1m8x2m).",
      "có dịch vụ thiết kế nội thất không": "Chúng tôi có dịch vụ thiết kế nội thất chuyên nghiệp theo yêu cầu của khách hàng.",
      "nội thất gỗ tự nhiên hay công nghiệp tốt hơn": "Gỗ tự nhiên bền hơn nhưng giá cao. Gỗ công nghiệp giá phải chăng, nhiều mẫu mã hiện đại.",
      "có thể đặt đóng nội thất theo yêu cầu không": "Có! Bạn có thể đặt đóng nội thất theo kích thước và kiểu dáng mong muốn.",
      "nội thất thông minh có những loại nào": "Chúng tôi có giường gấp thông minh, bàn ăn mở rộng, sofa giường và nhiều sản phẩm tối ưu không gian.",
      "cách làm sạch và bảo dưỡng sofa": "Sofa vải nên hút bụi thường xuyên, sofa da nên lau bằng khăn ẩm và dưỡng da định kỳ.",
      
      // Liên hệ và hỗ trợ
      "liên hệ|cách liên hệ|hotline": "Bạn có thể liên hệ với chúng tôi qua số điện thoại 0123 456 789 hoặc email: hujushop@gmail.com.",
      "có hỗ trợ bảo trì không|bảo trì thế nào": "Chúng tôi có dịch vụ bảo trì nội thất, bạn có thể liên hệ với chúng tôi để được tư vấn thêm.",
      "có chương trình khuyến mãi không|khuyến mãi hiện tại|giảm giá": "Chúng tôi thường xuyên có chương trình khuyến mãi, bạn hãy theo dõi trang web của chúng tôi để biết thêm chi tiết.",
      "có mẫu thử không|có thể xem mẫu không": "Chúng tôi có dịch vụ gửi mẫu thử cho một số sản phẩm, vui lòng liên hệ với chúng tôi để biết thêm thông tin.",
      "tôi muốn xem mẫu thực tế|có thể đến xem sản phẩm không": "Bạn có thể ghé cửa hàng chúng tôi tại 24/A Đường 3/2, Quận Ninh Kiều, TP.Cần Thơ để xem trực tiếp.",
      "có hỗ trợ lắp đặt không": "Có! Chúng tôi có đội ngũ lắp đặt chuyên nghiệp hỗ trợ tận nơi.",
      "mất bao lâu để giao hàng": "Thời gian giao hàng từ 3-5 ngày tùy khu vực. Chúng tôi sẽ liên hệ trước để xác nhận lịch giao hàng.",
        
      // Câu hỏi chung
      "cảm ơn|thanks|cám ơn nhé": "Rất vui khi được giúp bạn! Nếu cần gì hãy cứ hỏi tôi nhé.",
      "tạm biệt|bye|goodbye|hẹn gặp lại": "Tạm biệt! Chúc bạn một ngày tốt lành!",
      "bạn có thể giúp tôi tìm nội thất phù hợp không|tìm giúp tôi sản phẩm nội thất": "Chắc chắn rồi! Bạn có thể miêu tả nhu cầu của mình và tôi sẽ gợi ý sản phẩm phù hợp.",
      "có ưu đãi gì không|chương trình khuyến mãi hiện tại là gì": "Chúng tôi thường xuyên có ưu đãi hấp dẫn, bạn có thể xem chi tiết trên website của chúng tôi.",
      "có bảo hành tại nhà không": "Chúng tôi có thể cung cấp dịch vụ bảo hành tại nhà cho một số sản phẩm nhất định.",
      "có hợp đồng cung cấp nội thất không|có cung cấp nội thất cho dự án không": "Chúng tôi cung cấp dịch vụ cung cấp nội thất cho các dự án lớn, bạn có thể liên hệ để biết thêm chi tiết.",
      "có chương trình tư vấn thiết kế không": "Chúng tôi có chương trình tư vấn thiết kế nội thất cho khách hàng, hãy liên hệ để biết thêm thông tin.",
      "sản phẩm có thể tùy chỉnh không|có sản phẩm nào tùy chỉnh không": "Một số sản phẩm của chúng tôi có thể tùy chỉnh theo yêu cầu của khách hàng.",
      "có bảo trì định kỳ không|có dịch vụ bảo trì nào không": "Chúng tôi cung cấp dịch vụ bảo trì định kỳ cho các sản phẩm đã bán.",
      "có dịch vụ lắp đặt không|lắp đặt sản phẩm như thế nào": "Chúng tôi có dịch vụ lắp đặt miễn phí cho một số sản phẩm lớn như tủ, giường ngủ.",
      "có sản phẩm nào thân thiện với môi trường không": "Chúng tôi có nhiều sản phẩm được sản xuất từ nguyên liệu tái chế và thân thiện với môi trường.",
      "có sản phẩm nào dành cho trẻ em không": "Chúng tôi cung cấp nhiều sản phẩm nội thất an toàn và phù hợp cho trẻ em.",
      "có giảm giá cho khách hàng thân thiết không": "Có! Chúng tôi có chương trình tích điểm và giảm giá cho khách hàng thân thiết.",
      "tôi muốn hủy đơn hàng|có thể hủy đơn không": "Bạn có thể hủy đơn hàng trong vòng 24 giờ sau khi đặt hàng mà không mất phí.",
      "tôi cần hỗ trợ gấp|có ai hỗ trợ trực tiếp không": "Vui lòng gọi hotline 0123 456 789 để được hỗ trợ nhanh nhất.",
      
  };

  input = input.toLowerCase();

    for (let key in responses) {
        let regex = new RegExp(key, "i"); // Tạo regex từ key
        if (regex.test(input)) {
            return responses[key];
        }
    }

  return "Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.";
}


// Hiển thị tin nhắn của người dùng trong khung chat
function displayUserMessage(message) {
  let chat = document.getElementById("chat");
  let userMessage = document.createElement("div");
  userMessage.classList.add("message", "user");

  let userAvatar = document.createElement("div");
  userAvatar.classList.add("avatar");

  let userText = document.createElement("div");
  userText.classList.add("text");
  userText.innerHTML = message;

  userMessage.appendChild(userText);
  userMessage.appendChild(userAvatar);
  chat.appendChild(userMessage);
  chat.scrollTop = chat.scrollHeight;
}

// Hiển thị tin nhắn của bot trong khung chat
function displayBotMessage(message) {
  let chat = document.getElementById("chat");
  let botMessage = document.createElement("div");
  botMessage.classList.add("message", "bot");

  let botAvatar = document.createElement("div");
  botAvatar.classList.add("avatar");

  let botText = document.createElement("div");
  botText.classList.add("text");
  botText.innerHTML = message;

  botMessage.appendChild(botAvatar);
  botMessage.appendChild(botText);
  chat.appendChild(botMessage);
  chat.scrollTop = chat.scrollHeight;
}

// Gửi tin nhắn của người dùng và nhận phản hồi từ bot
function sendMessage() {
  let input = document.getElementById("input").value;
  if (input) {
      displayUserMessage(input);
      let output = chatbot(input);
      setTimeout(() => {
          displayBotMessage(output);
      }, 1000);
      document.getElementById("input").value = "";
  }
}

// Thêm sự kiện click cho nút gửi tin nhắn
document.getElementById("button").addEventListener("click", sendMessage);

// Thêm sự kiện nhấn phím Enter để gửi tin nhắn
document.getElementById("input").addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
      sendMessage();
  }
});
