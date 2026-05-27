<div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4" x-transition>
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden" @click.away="openEditModal = false">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-base text-slate-800">Chỉnh Sửa Sân Thể Thao</h3>
            <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
        </div>
        <form :action="'/admin/sports/' + editSport.id + '/update'" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tên sân thể thao *</label>
                <input type="text" name="name" x-model="editSport.name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-emerald-500" placeholder="Ví dụ: Sân Pickleball">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Mô tả đặc trưng</label>
                <textarea name="description" x-model="editSport.description" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-emerald-500" placeholder="Nhập mô tả tóm tắt..."></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ảnh đại diện sân</label>
                <input type="file" name="image" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-lg transition">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">Lưu lại</button>
            </div>
        </form>
    </div>
</div>