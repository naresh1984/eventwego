class AddFloatEvents < ActiveRecord::Migration
  def up
 remove_column :events, :latitude
 remove_column :events, :longitude
 add_column :events, :latitude, :float
 add_column :events, :longitude, :float
 
  end

  def down
  change_column :events, :latitude, :float
  change_column :events, :longitude, :float
  end
end
