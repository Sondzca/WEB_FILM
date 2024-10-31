import { faHourglass } from '@fortawesome/free-regular-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Link, NavLink } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { useMutation } from '@tanstack/react-query';
import axios from 'axios';
import { useQuery } from '@tanstack/react-query';
import TicketListByCategory from './TicketByCategory';
import { ConnectionProvider, useWallet, WalletProvider } from '@solana/wallet-adapter-react';
import { useEffect } from 'react';
import Wallet from '../connect/wallet';


const Header = () => {
    const { data } = useQuery({
        queryKey: ['CATEGORY'],
        queryFn: async () => {
            const res = await axios.get('http://127.0.0.1:8000/api/categories')
            return res.data
        }
    })
    
 return (
    <div>
      <div className="header">
            <div className="bg-[#2DC275] grid grid-cols-3 py-8 px-20">
                <div>
                    <h1 className="text-white text-3xl">
                        <Link to='/'>TICKETBOX</Link>
                    </h1>
                </div>
                <div className="flex">
                    <input type="search" placeholder="Vui lòng nhập tìm kiếm..." className="p-2 w-[70%] rounded-l border border-gray-300 focus:outline-none" />
                    <button className="bg-white p-2 rounded-r border border-gray-300 border-l-0 hover:bg-[#2DC275] hover:text-white">
                        <Link to="#">Search</Link>
                    </button>
                </div>
                <div className="flex items-center justify-end ">
                    <button className="border border-solid border-white rounded-2xl p-2 mr-8 text-white hover:bg-white hover:text-black">Đăng nhập</button>
                     <button className="border border-solid border-white rounded-2xl p-2 mr-8 text-white hover:bg-white hover:text-black">Đăng đăng ký</button>
                    <h2 className="cursor-pointer text-white">
                        <FontAwesomeIcon icon={faHourglass} className='text-white mr-1'/>
                       <Link to="/ticket">My Tickets</Link>
                    </h2>
                </div>
            </div>
            <div className="bg-black grid grid-cols-2 py-4 px-24">
                <div>
                    <ul>
                          {data?.map((category: any) => (
                              <li className="text-white px-4 inline-block hover:text-[#2DC275]" key={category.id}><NavLink to={`/tickets/${category.id}`}>{category.name}</NavLink></li>
                          ))}
                    </ul>
                </div>
                <div  className="text-right">
                    <Wallet />
                </div>
            </div>
        </div>
    </div>
  )
}

export default Header
