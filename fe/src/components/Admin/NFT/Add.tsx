import React from 'react'

const NFTAdd = () => {
  return (
    <div>
        <div className="">
            <h1 className="text-2xl font-bold mb-4">My NFT</h1>
            <div className="my-4">
                <label className="block text-sm font-bold mb-2">Name</label>
                <input type="text" name="" id="" placeholder="My NFT" className="w-full px-2 py-2 border border-solid border-gray-400 rounded-lg focus:outline-none  focus:border-green-500" />
            </div>
            <div className="my-4">
                <label className="block text-sm font-bold mb-2">Symbol</label>
                <input type="text" name="" id="" placeholder="NFT" className="w-full px-2 py-2 border border-solid border-gray-400 rounded-lg focus:outline-none  focus:border-green-500" />
            </div>
            <div className="my-4">
                <label className="block text-sm font-bold mb-2">Description</label>
                <textarea cols={30} rows={10} className="w-full px-2 py-2 border border-solid border-gray-400 rounded-lg focus:outline-none  focus:border-green-500" ></textarea>
            </div>
            <div className="my-4">
                <label className="block text-sm font-bold mb-2">Image</label>
                <input type="file" name="" id="" placeholder="" className="w-full px-2 py-2 border border-solid border-gray-400 rounded-lg focus:outline-none  focus:border-green-500" />
            </div>
            <div className="my-4">
                <label className="block text-sm font-bold mb-2">URL</label>
                <input type="text" name="" id="" placeholder="https://google.com" className="w-full px-2 py-2 border border-solid border-gray-400 rounded-lg focus:outline-none  focus:border-green-500" />
            </div>
            <div className="my-4 text-right">
                <button className="p-2 rounded-lg bg-blue-600 text-white hover:opacity-60">Create</button>
            </div>
        </div>
    </div>
  )
}

export default NFTAdd