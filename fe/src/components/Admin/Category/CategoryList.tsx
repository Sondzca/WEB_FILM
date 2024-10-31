import { Link, useNavigate } from "react-router-dom";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import axios from "axios";
import { toast } from "react-toastify";

const CategorytList = () => {
    const query = useQueryClient()
    const { data } = useQuery({
        queryKey: ['CATEGORY'],
        queryFn: async() => {
            const res = await axios.get('http://127.0.0.1:8000/api/categories')
            return res.data
        }
    })

    const { mutate } = useMutation({
      mutationFn: async(id: number) => {
          if(confirm('Bạn có muốn xóa ?')){
              await axios.delete(`http://127.0.0.1:8000/api/delete-category/${id}`)
          }
      },
      onSuccess: () => {
          toast.success('Xóa danh mục thành công!')
          query.invalidateQueries({
              queryKey: ['CATEGORY']
          })
      }
  })
  return (
    <>
      <div>Danh sách danh mục</div>
      <div className="my-8 flex justify-between">
        <Link to={`/admin/category/add`}
          className="text-white  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
        >
          thêm danh mục
        </Link>
      </div>
      
      <div className="relative shadow-md sm:rounded-lg">
        <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <td scope="col" className="px-6 py-3">STT</td>
              <td scope="col" className="px-6 py-3">
                Tên Danh Mục
              </td>
              <td scope="col" className="px-6 py-3">
                action
              </td>
            </tr>
          </thead>
          <tbody>
            {data?.map((category: any, index: number) => (
              <tr
                key={category._id}
                className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
              >
                <th className="px-6 py-4">{index + 1}</th>

                <th className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  <p className="inline-block">{category.name}</p>
                </th>

                <th className="px-6 py-4">
                  <div className="dropdown dropdown-hover">
                        <button className='w-[70px] h-[40px] bg-yellow-600 text-white rounded hover:opacity-60'>
                        <Link to={`/admin/category/edit/${category._id}`}>Sửa</Link>
                        </button>
                        <button onClick={() => mutate(category.id)} className='w-[70px] h-[40px] bg-red-600 text-white rounded hover:opacity-60'>
                          Xóa
                        </button>
                  </div>
                </th>
               
              </tr>
            ))
             }
          </tbody>
        </table>
      </div>
    </>
  );
};

export default CategorytList;
