<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('layoutAdmin/admin.css') }}"> <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> <!-- Custom CSS -->
</head>
<body>
    <div class="profile-page-wrapper w-full">
        <div class="">
            <div class="">
                <div class="flex justify-between border-b-2 bg-gray-100 border-solid border-gray-200 p-6 shadow-sm fixed w-full z-60">
                    <h1 class="text-black text-3xl">
                        <a href="/">TICKETBOX</a>
                    </h1>
                    <div class="text-right">
                        <!-- Wallet Component Here -->
                    </div>
                </div>
                <div class="w-full bg-white px-10 py-24">
                    <div class="title-area w-full flex justify-between items-center fixed">
                        <a href="/Admin"><h1 class="text-[22px] font-bold text-qblack">Your Dashboard</h1></a> 
                    </div>
                    <div class="profile-wrapper w-full flex space-x-10 mt-[3.5rem]">
                        <div class="w-[236px] min-h-[600px] border-r border-[rgba(0, 0, 0, 0.1)]">
                            <div class="flex flex-col space-y-10 fixed h-screen overflow-y-auto no-scrollbar">
                                <div class="item group">
                                    <a href="Active">
                                        <div class="flex space-x-3 items-center text-qgray hover:text-red-500">
                                            <span class="fa-solid fa-sliders"></span>
                                            <span class="font-normal text-base">Active</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="item group">
                                    <a href="collection">
                                        <div class="flex space-x-3 items-center text-qgray hover:text-red-500">
                                            <span class="fa-solid fa-image"></span>
                                            <span class="font-normal text-base">Collection</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="item group">
                                    <a href="category">
                                        <div class="flex space-x-3 items-center text-qgray hover:text-red-500">
                                            <span class="fa-solid fa-image"></span>
                                            <span class="font-normal text-base">Category</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="item group">
                                    <a href="ticket">
                                        <div class="flex space-x-3 items-center text-qgray hover:text-red-500">
                                            <span class="fa-solid fa-image"></span>
                                            <span class="font-normal text-base">Ticket</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="item group">
                                    <a href="events">
                                        <div class="flex space-x-3 items-center text-qgray hover:text-red-500">
                                            <span class="fa-solid fa-image"></span>
                                            <span class="font-normal text-base">Events</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <!-- Content Here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
