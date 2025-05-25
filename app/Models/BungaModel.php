<?php
namespace App\Models;
use CodeIgniter\Model;

class BungaModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kategori_id', 'nama', 'slug', 'deskripsi', 'harga', 'jumlah', 'gambar', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'nama'        => 'required|alpha_space|min_length[3]',
        'kategori_id' => 'required|integer',
        'harga'       => 'required|numeric',
        'jumlah'      => 'required|integer',
        'gambar'      => 'required|max_length[255]', 
    ];
    protected $validationMessages   = [
        'nama' => [
            'required' => 'Nama produk harus diisi.',
            'alpha_space' => 'Nama produk hanya boleh berisi huruf dan spasi.',
            'min_length' => 'Nama produk minimal 3 karakter.',
        ],
        'kategori_id' => [
            'required' => 'Kategori harus dipilih.',
            'integer'  => 'Kategori tidak valid.',
        ],
        'harga' => [
            'required' => 'Harga harus diisi.',
            'numeric'  => 'Harga harus berupa angka.',
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi.',
            'integer'  => 'Jumlah harus berupa angka.',
        ],
        'gambar' => [
            'uploaded' => 'Gambar harus diunggah.',
            'mime_in'  => 'File harus berupa gambar (jpg, jpeg, png).',
            'max_size' => 'Ukuran gambar maksimal 2MB.',
        ],
    ];

    // Validation rules to be applied when updating a record.
    // Here, 'gambar' is optional. If provided, it's validated.
    protected $updateRules = [
        'nama'        => 'permit_empty|alpha_space|min_length[3]',
        'kategori_id' => 'permit_empty|integer',
        'harga'       => 'permit_empty|numeric',
        'jumlah'      => 'permit_empty|integer',
        'gambar'      => 'permit_empty|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $beforeUpdate   = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['nama'])) {
            $slug = url_title($data['data']['nama'], '-', true);
            $data['data']['slug'] = $slug;
        }
        return $data;
    }
}