import { Route, Routes } from 'react-router-dom';
import './App.css';
import Music from './components/Music';
import LayoutWebsite from './layout/LayoutWebsite';
import Detail from './page/Detail';
import Home from './page/Home';
import LayoutAdmin from './layout/LayoutAdmin';
import NFTList from './components/Admin/NFT/List';
import NFTAdd from './components/Admin/NFT/Add';
import CollectionList from './components/Admin/Collection/List';
import CollectionAdd from './components/Admin/Collection/Add';
import CategoryAdd from './components/Admin/Category/CategoryAdd';
import TicketAdd from './components/Admin/Tickets/TicketAdd';
import CategorytList from './components/Admin/Category/CategoryList';
import TicketList from './components/Admin/Tickets/TicketList';
import TicketListByCategory from './components/TicketByCategory';
import TicketByCateTier2 from './components/TicketByCateTier2';
import Bill from './page/Bill';
import Active from './components/Admin/Active/Active';
function App() {
  return (
    <>
    <Routes>
      <Route path='/' element={<LayoutWebsite />}>
        <Route index element={<Home/>} />
        <Route path='/tickets/:cateID' element={< TicketByCateTier2 />}  />
        <Route path='/detail/:id'element={<Detail/>} /> 
        <Route path='/bill/' element={<Bill />} />
        <Route path='/ticket' element={<TicketList />} />
      </Route>

      <Route path='/admin' element={<LayoutAdmin />}>
          <Route path='nft' element={<NFTList />} />
          <Route path='nft/add' element={<NFTAdd />}/>
          <Route path='collection' element={<CollectionList />} />
          <Route path='collection/add' element={<CollectionAdd />} />
          <Route path='category' element={<CategorytList />} />
          <Route path='category/add' element={<CategoryAdd />} />
          <Route path='ticket' element={<TicketList />} />
          <Route path='ticket/add' element={<TicketAdd />} />
          <Route path='active' element={<Active />} />
      </Route>
    </Routes>
      
    </>
  )
}

export default App
