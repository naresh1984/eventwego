class AddlatToEvents < ActiveRecord::Migration
  def up
    change_column :events, :latitude, :string
    change_column :events, :longitude, :string
  end

  def down
  end
end
