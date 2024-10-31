import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import axios from 'axios';
import React from 'react'
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

const TicketAdd = () => {
    const query = useQueryClient()
    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm({
        defaultValues: {
            name: '',
            ngayphathanh: '',
            ngayketthuc:'',
            diachi: '',
            urlimage: '',
            giatien: '',
            mota: '',
            nguoitochuc: '',
            noitochuc: '',
            cateID: ''
        },
    });
    const navigate = useNavigate()
    const { data: categories } = useQuery({
        queryKey: ['CATEGORY'],
        queryFn: async() => {
            const res = await axios.get('http://127.0.0.1:8000/api/categories')
            return res.data
        }
    })
    const { mutate } = useMutation({
        mutationFn: async (ticket) => {
            const res = await axios.post('http://127.0.0.1:8000/api/ticket/add', ticket) 
            return res.data;
        },
        onSuccess: () => {
            toast.success('Thêm thành công!');
            query.invalidateQueries({
                queryKey: ['TICKET']
            })
        },
        onError: (error) => {
            toast.error('Thêm thất bại! ' + error.message);
        }
    });

    const onSubmit = (data: any) => {
        mutate(data);
        navigate('/admin/ticket')
    };
  return (
    <div>
      <div className="bg-white p-6 shadow rounded md:w-2/3">
                    <h2 className="text-2xl font-semibold mb-4">Tạo Vé</h2>
                    <form onSubmit={handleSubmit(onSubmit)}>
                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Danh mục
                        </label>
                        <select
                            {...register('cateID', { required: 'Danh mục là bắt buộc' })}
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option value="">---Chọn---</option>
                            {categories && categories.map((category: any) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>
                    </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Tên 
                            </label>
                            <input
                                {...register('name', { required: 'Tên vé là bắt buộc' })}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                            />
                        </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Giá 
                            </label>
                            <input
                                {...register('giatien', { required: 'Giá vé là bắt buộc' })}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                            />
                        </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Ảnh
                            </label>
                            <input
                                {...register('urlimage', { required: 'Địa chỉ vé là bắt buộc' })}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                               
                            />
                        </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Địa chỉ
                            </label>
                            <input
                                {...register('diachi', { required: 'Địa chỉ vé là bắt buộc' })}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                               
                            />
                        </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                              Ngày phát hành
                            </label>
                            <input
                              type="date"
                              {...register('ngayphathanh', { required: 'Ngày phát hành là bắt buộc' })}
                              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                            />
                          </div>

                          <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                              Ngày kết thúc
                            </label>
                            <input
                              type="date"
                              {...register('ngayketthuc', { required: 'Ngày kết thúc là bắt buộc' })}
                              className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                            />
                      </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Mô tả 
                            </label>
                            <textarea
                                cols={30}
                                rows={5}
                                {...register('mota', { required: 'Mô tả là bắt buộc' })}

                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                            ></textarea>
                        </div>
                          
                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Người tổ chức
                            </label>
                            <input
                                {...register('nguoitochuc', { required: 'Người tổ chức là bắt buộc' })}

                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                               
                            />
                        </div>

                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">
                                Nơi tổ chức
                            </label>
                            <input
                                {...register('noitochuc', { required: 'Nơi tổ chức là bắt buộc' })}

                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline disabled:cursor-not-allowed"
                               
                            />
                        </div>
                        <button type="submit" className="w-full py-2 px-4 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-lg focus:outline-none focus:ring-4 focus:ring-yellow-300 disabled:bg-blue-500">
                            Thêm
                        </button>
                    </form>
                </div>
    </div>
  )
}

export default TicketAdd
