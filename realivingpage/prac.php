<!DOCTYPE html>
<html lang="en" class="font-sans">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cabinet Planner</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 p-6" x-data="cabinetPlanner()">
  <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-4">🛠️ Cabinet Planner</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div>
        <label class="block font-medium">Cabinet Type</label>
        <select x-model="type" class="mt-1 w-full rounded border-gray-300">
          <option value="Base">Base</option>
          <option value="Wall">Wall</option>
          <option value="Tall">Tall</option>
        </select>
      </div>
      <div>
        <label class="block font-medium">Width (cm)</label>
        <input type="number" x-model="width" class="mt-1 w-full rounded border-gray-300" />
      </div>
      <div>
        <label class="block font-medium">Height (cm)</label>
        <input type="number" x-model="height" class="mt-1 w-full rounded border-gray-300" />
      </div>
      <div>
        <label class="block font-medium">Depth (cm)</label>
        <input type="number" x-model="depth" class="mt-1 w-full rounded border-gray-300" />
      </div>
      <div class="md:col-span-3">
        <button @click="addCabinet" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full mt-4">
          ➕ Add Cabinet
        </button>
      </div>
    </div>

    <h3 class="text-xl font-semibold mb-2">🗂️ Layout</h3>
    <div class="flex flex-wrap gap-4">
      <template x-for="(cab, index) in cabinets" :key="index">
        <div class="bg-gray-200 rounded p-3 w-40">
          <div class="font-bold text-sm text-center mb-2" x-text="cab.type + ' Cabinet'"></div>
          <div class="text-center text-sm">
            <p><strong>W:</strong> <span x-text="cab.width"></span>cm</p>
            <p><strong>H:</strong> <span x-text="cab.height"></span>cm</p>
            <p><strong>D:</strong> <span x-text="cab.depth"></span>cm</p>
          </div>
          <button @click="removeCabinet(index)" class="text-red-500 text-sm mt-2 block mx-auto">Remove</button>
        </div>
      </template>
    </div>
  </div>

  <script>
    function cabinetPlanner() {
      return {
        type: 'Base',
        width: 60,
        height: 80,
        depth: 60,
        cabinets: [],
        addCabinet() {
          this.cabinets.push({
            type: this.type,
            width: this.width,
            height: this.height,
            depth: this.depth,
          });
        },
        removeCabinet(index) {
          this.cabinets.splice(index, 1);
        }
      };
    }
  </script>
</body>
</html>
