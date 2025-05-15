<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Client</h2>
            <span class="close">&times;</span>
        </div>
        <form method="post" id="editForm" class="space-y-4">
            <input type="hidden" name="update_id" id="edit_id">
            
            <div>
                <label for="edit_clientname" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                <input type="text" name="edit_clientname" id="edit_clientname" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>
            
            <div>
                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="edit_status" id="edit_status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="New Client">New Client</option>
                    <option value="Old Client">Old Client</option>
                </select>
            </div>
            
            <div>
                <label for="edit_nameproject" class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input type="text" name="edit_nameproject" id="edit_nameproject"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>
            
            <div>
                <label for="edit_client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                <select name="edit_client_type" id="edit_client_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="Noblehome">Noblehome</option>
                    <option value="Realiving">Realiving</option>
                </select>
            </div>
            
            <div>
                <label for="edit_client_class" class="block text-sm font-medium text-gray-700 mb-1">Client Classification</label>
                <select name="edit_client_class" id="edit_client_class"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="VIP">VIP</option>
                    <option value="Regular">Regular</option>
                    <option value="Walk-in">Walk-in</option>
                    <option value="Returning">Returning</option>
                </select>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 font-medium rounded-lg mr-2">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-150">
                    <i class="fas fa-save mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>