
import { BannerImage, MusicImage } from './icon/image'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCakeCandles } from '@fortawesome/free-solid-svg-icons'
import { faArrowCircleRight } from '@fortawesome/free-solid-svg-icons/faArrowCircleRight'
import { Link } from 'react-router-dom'
import { useState,useEffect } from 'react'
import axios from 'axios'
import { formatDate, formatPrice } from '../utils/format'
    

const TicketListByCategory = ({cateID}:any ) => {
    
    const [tickets, setTickets] = useState([]);
    const [categoryName, setCategoryName] = useState('');
    useEffect(() => {
        const fetchTickets = async () => {
            try {
                
                const response = await axios.get(`http://127.0.0.1:8000/api/tickets-by-category/${cateID}`);
                setTickets(response.data.tickets);
            } catch (error) {
                console.error('Error fetching tickets:', error);
            }
        };
        fetchTickets();
        const fetchCate = async ()=>{
            try {
                const categoryResponse = await axios.get(`http://127.0.0.1:8000/api/category/detail/${cateID}`);

                
                if (Array.isArray(categoryResponse.data.categories) && categoryResponse.data.categories.length > 0) {
                   
                    categoryResponse.data.categories.forEach((category: any) => {
                        setCategoryName( category.name);
                        console.log(categoryName);

                    });
                } else {
                    console.log('Không có danh mục được trả về hoặc dữ liệu không hợp lệ.');
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }
        fetchCate();
    }, [cateID]);
    return (
      
        <div>
            <div className="my-6">
                <div className="flex justify-between mb-2">
                    <div>
                       
                           <h2 className="font-bold text-xl text-white">{categoryName} </h2>
                       
                    </div>
                    <h3 className="text-lg text-gray-400">
                        View more
                        <FontAwesomeIcon icon={faArrowCircleRight} className='ml-1' />

                    </h3>
                </div>
                <div className="grid grid-cols-4 gap-6">
                    {tickets?.map((ticket: any) => (
                        <div className="" key={ticket.id}>
                            <Link to={`/detail/${ticket.id}`}><img src={BannerImage} alt="" className="w-full rounded-xl" /></Link>
                            <h3 className="font-bold text-sm mt-2 text-white">{ticket.name}</h3>
                            <p className="text-[#2DC275] my-2 font-bold">{formatPrice(ticket.giatien/1000000)} SOL</p>
                            <span className="text-white">
                                <FontAwesomeIcon icon={faCakeCandles} className='mr-1' />
                                {formatDate(ticket.ngayphathanh)}
                            </span>
                            
                        </div>
                       
                    ))}   
                </div>
            </div>
        </div>
    )
}


export default TicketListByCategory
