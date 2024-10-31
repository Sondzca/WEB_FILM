import { useLocation } from 'react-router-dom';
import Banner from '../components/Banner';
const Bill = () => {
    const location = useLocation();
    const { postData } = location.state;
    return (
        <div>
            <Banner />
            <h1>Chi tiết hóa đơn</h1>
            <div className="relative shadow-md sm:rounded-lg">
                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <td scope="col" className="px-6 py-3">STT</td>
                        <td scope="col" className="px-6 py-3">Địa chỉ Ví</td>
                        <td scope="col" className="px-6 py-3">Tên vé</td>
                        <td scope="col" className="px-6 py-3">Số lượng</td>
                        <td scope="col" className="px-6 py-3">Ảnh</td>
                        <td scope="col" className="px-6 py-3">Mô tả</td>
                    </tr>


                {postData?.map((data:any,index:any)=>{
                    <tr
                        key={data.id}
                        className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                    >
                        <th className="px-6 py-4">{index + 1}</th>
                        <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <p className="inline-block">{data.wallet}</p>
                        </th>
                        
                        <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <p className="inline-block">{data.name}</p>
                        </th>
                        <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img src={data.soluong} className="inline-block" />
                        </th>
                        <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <p className="inline-block">{data.image}</p>
                        </th>
                        <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <p className="inline-block">{data.mota}</p>
                        </th>
                    </tr>
            })}
                    </thead>
                </table>
            </div>
        </div>
    );
};

export default Bill;