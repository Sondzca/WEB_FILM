import { useMutation, useQueryClient } from '@tanstack/react-query';
import axios from 'axios';
import React from 'react';
import { useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';

const CategoryAdd = () => {
    const query = useQueryClient()
    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm({
        defaultValues: {
            name: '',
        },
    });
    const navigate = useNavigate()

    const { mutate } = useMutation({
        mutationFn: async (category) => {
            const res = await axios.post('http://127.0.0.1:8000/api/category/add', category)
            return res.data;
        },
        onSuccess: () => {
            toast.success("Thêm thành công!");
            query.invalidateQueries({
                queryKey: ['CATEGORY']
            })
        },
        onError: (error) => {
            toast.error('Thêm thất bại! ' + error.message);
        }
    });
    

    const onSubmit = (data: any) => {
        mutate(data);
        navigate('/admin/category')
    };

    return (
        <div>
            <div>
                <form onSubmit={handleSubmit(onSubmit)}>
                    <div>
                        <label className="block text-gray-700 text-sm font-bold mb-2">
                            Tên Danh Mục:
                        </label>
                        <input
                {...register('name', { required: 'Tên danh mục là bắt buộc' })}
                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            />

                        {errors.name && <span className="text-red-500">Tên danh mục là bắt buộc.</span>}
                    </div>

                    <button className="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                        Thêm
                    </button>
                </form>
            </div>
        </div>
    );
};

export default CategoryAdd;
