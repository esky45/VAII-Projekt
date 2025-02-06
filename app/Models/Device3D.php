<?php

    class Device3D extends Model
    {
        protected $fillable = [
            'type', 'status', 'position', 'room_id'
        ];
    
        protected $casts = [
            'position' => 'array'
        ];
    
        public function room()
        {
            return $this->belongsTo(Room::class);
        }
    }