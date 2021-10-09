public function cetak(Request $request)
    {
        $datas = [
            'sekolah' => [],
            'wali_kelas' => [],
            
            'rapor' => [
                'siswa' => [],
                'pts' => [
                    'nilais' => [
                       
                        'wajib' => [
                            'pabp' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                               
                            ]
                        ],
                        'mulok' => [
                            'bd' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                                
                            ],
                            'ben' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                               
                            ],
                            
                        ]
                    ],
                    'saran' => '',
                    'tanggal_rapor' => '',
                ],
                'pas' => [
                    'nilais' => [
                        'sikaps' => [
                            'k1' => [
                                'label' => '',
                                'deskripsi' => ''
                            ],
                            'k2' => [
                                'label' => '',
                                'deskripsi' => ''
                            ],
                            
                        ],
                        'wajib' => [
                            'pabp' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                                'k4' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                                
                            ]
                        ],
                        'mulok' => [
                            'bd' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                                'k4' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                            ],
                            'ben' => [
                                'label' => '',
                                'k3' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                                'k4' => [
                                    'nilai' => 0,
                                    'predikat' => '',
                                    'deskripsi' => ''
                                ],
                            ],
                            
                        ]
                    ],
                    'ekskuls' => [
                          'pramuka' => [
                        'label' => '',
                        'keterangan' => ''
                        ],
                    ],
                    'sarans' => '',
                    'fisik' => [
                        'tb' => '',
                        'bb' => ''
                    ],
                    'kesehatan' => [
                        'pendengaran' => '',
                    ],
                    'prestasis' => [
                        'kesenian' => [
                            'label' => '',
                            'keterangan' => ''
                        ],
                    ],
                    'absensis' => [
                        's' => 0,
                        'i' => 0,
                        'a' => 0
                    ],
                    'tgl_rapor' => ''
                ],
            ]

        ];

    }