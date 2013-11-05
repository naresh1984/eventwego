class AddFloatEvents < ActiveRecord::Migration
  def up
 change_column :events, :latitude, :float
 change_column :events, :longitude, :float
  end

  def down
  end
end
