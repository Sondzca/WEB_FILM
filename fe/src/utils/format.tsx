//Định dạng ngày/tháng/năm
export const formatDate = (dateString: any) => {
    return new Date(dateString).toLocaleDateString('vi-VN');
};

// Định dạng giá
export const formatPrice = (price: any) => {
    return parseFloat(price).toLocaleString('vi-VN', { minimumFractionDigits: 0 });
};