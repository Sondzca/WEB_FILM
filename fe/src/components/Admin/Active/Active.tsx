import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

// Định nghĩa kiểu dữ liệu cho mảng actives
interface ActiveItem {
    id: number;
    link: string;
    confirmed: boolean;
}

const Active = () => {
    const [actives, setActive] = useState<ActiveItem[]>([]);

    const handleConfirmation = (index: number) => {
        const updatedActives = [...actives];
        updatedActives[index].confirmed = true;
        setActive(updatedActives);
    };

    useEffect(() => {
        const fetchActive = async () => {
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/active/list`);
                setActive(response.data.data);
            } catch (error) {
                console.error('Error fetching Active:', error);
            }
        };
        fetchActive();
    }, []);

    return (
        <>
            <div>Active</div>
            <div className="relative shadow-md sm:rounded-lg">
                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <td scope="col" className="px-6 py-3">STT</td>
                            <td scope="col" className="px-6 py-3">ID</td>
                            <td scope="col" className="px-6 py-3">Link xác nhận</td>
                        </tr>
                    </thead>
                    <tbody>
                        {actives?.map((active, index) => (
                            <tr key={active.id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th className="px-6 py-4">{index + 1}</th>
                                <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <p className="inline-block">{active.id}</p>
                                </th>
                                <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <p className="inline-block">{active.link}</p>
                                </th>
                                <th className="px-6 py-4">
                                    <div className="dropdown dropdown-hover">
                                        <button className={`w-[70px] h-[40px] bg-${active.confirmed ? 'green' : 'yellow'}-600 text-white rounded hover:opacity-60`} onClick={() => handleConfirmation(index)}>
                                            <Link to={`${active.link}`}>Xác nhận</Link>
                                        </button>
                                        <button className={`w-[70px] h-[40px] bg-${active.confirmed ? 'green' : 'red'}-600 text-white rounded hover:opacity-60`}>
                                            {active.confirmed ? 'Đã xác nhận' : 'Chưa xác nhận'}
                                        </button>
                                    </div>
                                </th>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </>
    );
};

export default Active;