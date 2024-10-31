import Banner from '../components/Banner'
import axios from 'axios'
import { useQuery } from '@tanstack/react-query'
import TicketListByCategory from '../components/TicketByCategory'
import { useEffect } from 'react'
const Home = () => {
  const { data:categories } = useQuery({
    queryKey: ['CATEGORY'],
    queryFn: async () => {
      const res = await axios.get('http://127.0.0.1:8000/api/categories')
      return res.data
    }
  })
  // useEffect(() => {
  //   if (categories) {
  //     categories.forEach((category :any) => {
  //       console.log(category.id);
  //     });
  //   }
  // }, [categories]);
  return (
    <div className='w-full'>
        <Banner />
      {categories?.map((category: any) => (
        <div key={category.id}>
          <TicketListByCategory cateID={category.id} />
        </div>
      ))}
    </div>
  )
}

export default Home
